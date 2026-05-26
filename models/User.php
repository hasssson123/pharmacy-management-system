<?php
/**
 * User Model
 * نموذج المستخدمات
 */

class User extends BaseModel {
    
    protected $table = 'users';

    /**
     * Find by Username
     * البحث بالاسم المستخدم
     */
    public function findByUsername($username) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Find by Email
     * البحث بالبريد
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get Company Users
     * الحصول على مستخدمي الشركة
     */
    public function getCompanyUsers($companyId, $limit = null, $offset = 0) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE company_id = ?";
            
            if ($limit) {
                $query .= " LIMIT ? OFFSET ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$companyId, $limit, $offset]);
            } else {
                $stmt = $this->db->prepare($query);
                $stmt->execute([$companyId]);
            }
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get Branch Users
     * الحصول على مستخدمي الفرع
     */
    public function getBranchUsers($branchId, $limit = null, $offset = 0) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE branch_id = ?";
            
            if ($limit) {
                $query .= " LIMIT ? OFFSET ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$branchId, $limit, $offset]);
            } else {
                $stmt = $this->db->prepare($query);
                $stmt->execute([$branchId]);
            }
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
