<?php

class User
{
    private PDO $db;
    private $logger;

    public function __construct(PDO $db, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function create(string $name, ?string $bitrixId)
    {
        $sql = "INSERT INTO users (name, bitrix_id) VALUES (:name, :bitrix_id)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':name' => $name, ':bitrix_id' => $bitrixId]);
            $this->logger->info("New user created: $name (Bitrix ID: $bitrixId)");
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error("User creation error: " . $e->getMessage());
            return false;
        }
    }

    public function getUserBitrixId(string $name): ?string
    {
        $sql = "SELECT bitrix_id FROM users WHERE name = :name";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':name' => $name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $this->logger->info("User fetched: $name");
                return $result['bitrix_id'];
            }
            $this->logger->warning("No user found: $name");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("User retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT id, name, bitrix_id FROM users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->logger->info("User fetched with ID: $id");
                return $user;
            }
            $this->logger->warning("No user found with ID: $id");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("User retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function getByBitrixId(string $bitrixId): ?array
    {
        $sql = "SELECT id, name, bitrix_id FROM users WHERE bitrix_id = :bitrix_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':bitrix_id' => $bitrixId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->logger->info("User fetched with Bitrix ID: $bitrixId");
                return $user;
            }
            $this->logger->warning("No user found with Bitrix ID: $bitrixId");
            return null;
        } catch (PDOException $e) {
            $this->logger->error("User retrieval error: " . $e->getMessage());
            return null;
        }
    }

    public function update(int $id, ?string $name, ?string $bitrixId = null): bool
    {
        $fields = array_filter(['name' => $name, 'bitrix_id' => $bitrixId], fn($v) => $v !== null);
        if (empty($fields)) return false;

        $set = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($fields)));
        $sql = "UPDATE users SET $set WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $params = array_map(fn($key) => ":$key", array_keys($fields));
            $stmt->execute(array_combine($params, array_values($fields)) + [':id' => $id]);
            $this->logger->info("User updated with ID: $id");
            return true;
        } catch (PDOException $e) {
            $this->logger->error("User update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $this->logger->info("User deleted with ID: $id");
            return true;
        } catch (PDOException $e) {
            $this->logger->error("User deletion error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll(int $limit = 1000, int $offset = 0): array
    {
        $sql = "SELECT id, name, bitrix_id FROM users LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':limit' => $limit, ':offset' => $offset]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->logger->info("Fetched $limit users from offset $offset");
            return $users ?: [];
        } catch (PDOException $e) {
            $this->logger->error("Users fetch error: " . $e->getMessage());
            return [];
        }
    }

    public function bulkImport(array $users): array
    {
        $this->db->beginTransaction();
        $successCount = 0;
        $errors = [];

        $sql = "INSERT INTO users (name, bitrix_id) VALUES (:name, :bitrix_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($users as $index => $user) {
            try {
                $stmt->execute([
                    ':name' => $user['name'] ?? null,
                    ':bitrix_id' => $user['bitrix_id'] ?? null
                ]);
                $successCount++;
            } catch (PDOException $e) {
                $errors[] = ['index' => $index, 'error' => $e->getMessage(), 'user' => $user];
            }
        }

        if (empty($errors)) {
            $this->db->commit();
            $this->logger->info("Bulk import successful: $successCount records");
            return ['success' => true, 'total_records' => count($users), 'imported_records' => $successCount];
        }

        $this->db->rollBack();
        $this->logger->error("Bulk import failed. Successful: $successCount, Failed: " . count($errors));
        return [
            'success' => false,
            'total_records' => count($users),
            'imported_records' => $successCount,
            'failed_records' => count($errors),
            'errors' => $errors
        ];
    }
}
