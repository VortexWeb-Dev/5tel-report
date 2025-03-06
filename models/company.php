<?php

class Company
{
    private PDO $db;
    private $logger;

    public function __construct(PDO $db, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function create(string $name, string $mid, ?string $responsiblePerson, ?string $responsiblePersonBitrixId)
    {
        $sql = "INSERT INTO company (name, mid, responsible_person, responsible_person_bitrix_id) 
                VALUES (:name, :mid, :responsible_person, :responsible_person_bitrix_id)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':mid' => $mid,
                ':responsible_person' => $responsiblePerson,
                ':responsible_person_bitrix_id' => $responsiblePersonBitrixId
            ]);
            $this->logger->info("New company created: $name (MID: $mid)");
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error("Company creation error: " . $e->getMessage());
            return false;
        }
    }

    public function getResponsiblePerson(string $mid): ?array
    {
        $sql = "SELECT responsible_person_bitrix_id, responsible_person FROM company WHERE mid = :mid";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':mid' => $mid]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($company) {
                $this->logger->info("Company fetched with MID: $mid");
                return $company;
            }
            $this->logger->warning("No company found with MID: $mid");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("Company retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id, name, mid, responsible_person, responsible_person_bitrix_id 
                FROM company WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($company) {
                $this->logger->info("Company fetched with ID: $id");
                return $company;
            }
            $this->logger->warning("No company found with ID: $id");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("Company retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function getByMid(string $mid): ?array
    {
        $sql = "SELECT id, name, mid, responsible_person, responsible_person_bitrix_id 
                FROM company WHERE mid = :mid";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':mid' => $mid]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($company) {
                $this->logger->info("Company fetched with MID: $mid");
                return $company;
            }
            $this->logger->warning("No company found with MID: $mid");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("Company retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, ?string $name = null, ?string $mid = null, ?string $responsiblePerson = null, ?string $responsiblePersonBitrixId = null): bool
    {
        $fields = array_filter([
            'name' => $name,
            'mid' => $mid,
            'responsible_person' => $responsiblePerson,
            'responsible_person_bitrix_id' => $responsiblePersonBitrixId
        ], fn($v) => $v !== null);

        if (empty($fields)) return false;

        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($fields)));
        $sql = "UPDATE company SET $set WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $params = array_map(fn($key) => ":$key", array_keys($fields));
            $stmt->execute(array_combine($params, array_values($fields)) + [':id' => $id]);
            $this->logger->info("Company updated with ID: $id");
            return true;
        } catch (PDOException $e) {
            $this->logger->error("Company update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM company WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $this->logger->info("Company deleted with ID: $id");
            return true;
        } catch (PDOException $e) {
            $this->logger->error("Company deletion error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(int $limit = 1000, int $offset = 0): array
    {
        $sql = "SELECT id, name, mid, responsible_person, responsible_person_bitrix_id 
                FROM company LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':limit' => $limit, ':offset' => $offset]);
            $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->logger->info("Fetched $limit companies from offset $offset");
            return $companies ?: [];
        } catch (PDOException $e) {
            $this->logger->error("Companies fetch error: " . $e->getMessage());
            return [];
        }
    }
}
