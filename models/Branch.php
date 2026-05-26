<?php
/**
 * Branch Model
 * نموذج الفرع
 */

class Branch extends BaseModel {
    
    protected $table = 'branches';

    /**
     * Get Company Branches
     * الحصول على فروع الشركة
     */
    public function getCompanyBranches($companyId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} WHERE company_id = ? AND status = 'active' ORDER BY name ASC"
            );
            $stmt->execute([$companyId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get Branch by ID with Details
     */
    public function getBranchWithDetails($branchId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT b.*, c.name as company_name, c.ar_name as company_ar_name 
                 FROM {$this->table} b 
                 JOIN companies c ON b.company_id = c.id 
                 WHERE b.id = ?"
            );
            $stmt->execute([$branchId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
}
