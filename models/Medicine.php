<?php
/**
 * Medicine Model
 * نموذج الأدوية
 */

class Medicine extends BaseModel {
    
    protected $table = 'medicines';

    /**
     * Find by Barcode
     * البحث بالباركود
     */
    public function findByBarcode($barcode, $companyId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} WHERE barcode = ? AND company_id = ?"
            );
            $stmt->execute([$barcode, $companyId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get Company Medicines
     * الحصول على أدوية الشركة
     */
    public function getCompanyMedicines($companyId, $limit = null, $offset = 0, $status = 'active') {
        try {
            $query = "SELECT * FROM {$this->table} WHERE company_id = ? AND status = ?";
            
            if ($limit) {
                $query .= " LIMIT ? OFFSET ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$companyId, $status, $limit, $offset]);
            } else {
                $stmt = $this->db->prepare($query);
                $stmt->execute([$companyId, $status]);
            }
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get Expired Medicines
     * الحصول على الأدوية منتهية الصلاحية
     */
    public function getExpiredMedicines($companyId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM {$this->table} WHERE company_id = ? AND expiry_date < CURDATE() AND status = 'active'"
            );
            $stmt->execute([$companyId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
