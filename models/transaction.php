<?php

class Transaction
{
    private PDO $db;
    private $logger;

    public function __construct(PDO $db, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function create(array $data)
    {
        $sql = "
            INSERT INTO transaction (
                statement_month, mid, dba, status, local_currency, entity, sales_rep_code, 
                open_date, tier_code, card_plan, first_batch_date, base_msc_rate, base_msc_pi, 
                ex_rate, ex_pi, int_lc, asmt_lc, base_msc_amt, exception_msc_amt, msc_amt, 
                sales_volume, sales_trxn, plan, primary_rate, secondary_rate, residual_per_item, 
                revenue_share, earnings_local_currency, commission, responsible_person, 
                responsible_person_bitrix_id
            ) VALUES (
                :statement_month, :mid, :dba, :status, :local_currency, :entity, :sales_rep_code, 
                :open_date, :tier_code, :card_plan, :first_batch_date, :base_msc_rate, :base_msc_pi, 
                :ex_rate, :ex_pi, :int_lc, :asmt_lc, :base_msc_amt, :exception_msc_amt, :msc_amt, 
                :sales_volume, :sales_trxn, :plan, :primary_rate, :secondary_rate, :residual_per_item, 
                :revenue_share, :earnings_local_currency, :commission, :responsible_person, 
                :responsible_person_bitrix_id
            )";

        $params = array_map(fn($key) => $data[$key] ?? null, [
            'statement_month',
            'mid',
            'dba',
            'status',
            'local_currency',
            'entity',
            'sales_rep_code',
            'open_date',
            'tier_code',
            'card_plan',
            'first_batch_date',
            'base_msc_rate',
            'base_msc_pi',
            'ex_rate',
            'ex_pi',
            'int_lc',
            'asmt_lc',
            'base_msc_amt',
            'exception_msc_amt',
            'msc_amt',
            'sales_volume',
            'sales_trxn',
            'plan',
            'primary_rate',
            'secondary_rate',
            'residual_per_item',
            'revenue_share',
            'earnings_local_currency',
            'commission',
            'responsible_person',
            'responsible_person_bitrix_id'
        ]);

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_combine(
                array_map(fn($key) => ":$key", array_keys($data)),
                $params
            ));
            $this->logger->info("New transaction created for MID: {$data['mid']}");
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error("Transaction creation error: " . $e->getMessage());
            return false;
        }
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM transaction WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $this->logger->info("Transaction fetched with ID: $id");
                return $row;
            }
            $this->logger->warning("No transaction found with ID: $id");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("Transaction retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function getByMid(string $mid): array
    {
        $sql = "SELECT * FROM transaction WHERE mid = :mid";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':mid' => $mid]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->logger->info("Transactions fetched for MID: $mid");
            return $transactions ?: [];
        } catch (PDOException $e) {
            $this->logger->error("Error fetching transactions by MID: " . $e->getMessage());
            return [];
        }
    }

    public function update(int $id, array $data): bool
    {
        $fields = array_map(fn($key) => "$key = :$key", array_keys($data));
        $sql = "UPDATE transaction SET " . implode(', ', $fields) . " WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $params = array_combine(
                array_map(fn($key) => ":$key", array_keys($data)),
                array_values($data)
            );
            $params[':id'] = $id;
            $result = $stmt->execute($params);
            if ($result) {
                $this->logger->info("Transaction updated with ID: $id");
                return true;
            }
            $this->logger->error("Failed to update transaction");
            return false;
        } catch (PDOException $e) {
            $this->logger->error("Transaction update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM transaction WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':id' => $id]);
            if ($result) {
                $this->logger->info("Transaction deleted with ID: $id");
                return true;
            }
            $this->logger->error("Failed to delete transaction");
            return false;
        } catch (PDOException $e) {
            $this->logger->error("Transaction deletion error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(int $limit = 50, int $offset = 0, array $select = ['*'], array $filter = [], array $order = []): array
    {
        $where = [];
        $params = [];
        foreach ($filter as $key => $value) {
            [$col, $op] = explode('|', $key);
            if ($op === 'IN' && is_array($value)) {
                $placeholders = implode(', ', array_map(fn($i) => ":$col$i", range(1, count($value))));
                $where[] = "$col IN ($placeholders)";
                foreach ($value as $i => $val) {
                    $params[":$col" . ($i + 1)] = $val;
                }
            } elseif ($op === 'ILIKE') {
                $where[] = "$col ILIKE :$col";
                $params[":$col"] = $value;
            } else {
                $where[] = "$col = :$col";
                $params[":$col"] = $value;
            }
        }

        $orderBy = array_map(fn($k, $v) => "$k $v", array_keys($order), $order);
        $sql = sprintf(
            "SELECT %s, COUNT(*) OVER() AS total_count, SUM(earnings_local_currency) OVER() AS total_earnings, 
             SUM(commission) OVER() AS total_commission FROM transaction %s %s LIMIT :limit OFFSET :offset",
            implode(',', $select),
            $where ? 'WHERE ' . implode(' AND ', $where) : '',
            $orderBy ? 'ORDER BY ' . implode(', ', $orderBy) : ''
        );

        try {
            $stmt = $this->db->prepare($sql);
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
            $stmt->execute($params);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($transactions) {
                $this->logger->info("Fetched $limit transactions from offset $offset");
                return [
                    'transactions' => $transactions,
                    'total_count' => $transactions[0]['total_count'],
                    'total_earnings' => $transactions[0]['total_earnings'],
                    'total_commission' => $transactions[0]['total_commission']
                ];
            }
            return ['transactions' => [], 'total_count' => 0, 'total_earnings' => 0, 'total_commission' => 0];
        } catch (PDOException $e) {
            $this->logger->error("Error fetching transactions: " . $e->getMessage());
            return ['transactions' => [], 'total_count' => 0, 'total_earnings' => 0, 'total_commission' => 0];
        }
    }
}
