<?php
/**
 * Base Model
 * نموذج أساسي
 */

class BaseModel {
    
    protected $table;
    protected $db;
    protected $id;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find by ID
     * البحث بواسطة ID
     */
    public function find($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get All
     * الحصول على الكل
     */
    public function getAll($limit = null, $offset = 0) {
        try {
            $query = "SELECT * FROM {$this->table}";
            
            if ($limit) {
                $query .= " LIMIT ? OFFSET ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$limit, $offset]);
            } else {
                $stmt = $this->db->query($query);
            }
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Count
     * عد السجلات
     */
    public function count($where = []) {
        try {
            $query = "SELECT COUNT(*) FROM {$this->table}";
            
            if (!empty($where)) {
                $conditions = [];
                $values = [];
                foreach ($where as $column => $value) {
                    $conditions[] = "$column = ?";
                    $values[] = $value;
                }
                $query .= " WHERE " . implode(" AND ", $conditions);
                $stmt = $this->db->prepare($query);
                $stmt->execute($values);
            } else {
                $stmt = $this->db->query($query);
            }
            
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Create
     * إنشاء سجل جديد
     */
    public function create($data) {
        try {
            $columns = array_keys($data);
            $placeholders = array_fill(0, count($columns), '?');
            
            $query = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") 
                     VALUES (" . implode(',', $placeholders) . ")";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute(array_values($data));
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Update
     * تحديث سجل
     */
    public function update($id, $data) {
        try {
            $sets = [];
            $values = [];
            
            foreach ($data as $column => $value) {
                $sets[] = "$column = ?";
                $values[] = $value;
            }
            
            $values[] = $id;
            
            $query = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete
     * حذف سجل
     */
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Where Query
     * استعلام WHERE
     */
    public function where($conditions, $limit = null, $offset = 0) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE ";
            $where_clauses = [];
            $values = [];
            
            foreach ($conditions as $column => $value) {
                $where_clauses[] = "$column = ?";
                $values[] = $value;
            }
            
            $query .= implode(" AND ", $where_clauses);
            
            if ($limit) {
                $query .= " LIMIT ? OFFSET ?";
                $values[] = $limit;
                $values[] = $offset;
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($values);
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
