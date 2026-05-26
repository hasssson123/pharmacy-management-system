<?php
/**
 * Company Model
 * نموذج الشركات
 */

class Company extends BaseModel {
    
    protected $table = 'companies';

    /**
     * Get Branches
     * الحصول على الفروع
     */
    public function getBranches($companyId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM branches WHERE company_id = ? ORDER BY name ASC");
            $stmt->execute([$companyId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Count Branches
     * عد الفروع
     */
    public function countBranches($companyId) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM branches WHERE company_id = ?");
            $stmt->execute([$companyId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
}
