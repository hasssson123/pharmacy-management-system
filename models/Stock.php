<?php
/**
 * Stock Model
 * نموذج المخزون
 */

class Stock extends BaseModel {
    
    protected $table = 'stock';

    /**
     * Get Branch Stock
     * الحصول على مخزون الفرع
     */
    public function getBranchStock($branchId, $limit = null, $offset = 0) {
        try {
            $query = "SELECT s.*, m.name, m.ar_name, m.barcode, m.price, m.min_stock 
                      FROM {$this->table} s 
                      JOIN medicines m ON s.medicine_id = m.id 
                      WHERE s.branch_id = ?";
            
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

    /**
     * Get Low Stock
     * الحصول على الأدوية الناقصة
     */
    public function getLowStock($branchId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT s.*, m.name, m.ar_name, m.barcode, m.price, m.min_stock 
                 FROM {$this->table} s 
                 JOIN medicines m ON s.medicine_id = m.id 
                 WHERE s.branch_id = ? AND s.available_quantity <= m.min_stock"
            );
            $stmt->execute([$branchId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Add Stock
     * إضافة للمخزون
     */
    public function addStock($branchId, $medicineId, $quantity) {
        try {
            $this->db->beginTransaction();
            
            // Check if stock exists
            $stmt = $this->db->prepare(
                "SELECT id FROM {$this->table} WHERE branch_id = ? AND medicine_id = ?"
            );
            $stmt->execute([$branchId, $medicineId]);
            $stock = $stmt->fetch();
            
            if ($stock) {
                // Update existing stock
                $stmt = $this->db->prepare(
                    "UPDATE {$this->table} SET quantity = quantity + ?, last_restock_date = NOW() 
                     WHERE branch_id = ? AND medicine_id = ?"
                );
                $stmt->execute([$quantity, $branchId, $medicineId]);
            } else {
                // Create new stock
                $stmt = $this->db->prepare(
                    "INSERT INTO {$this->table} (branch_id, medicine_id, quantity, last_restock_date) 
                     VALUES (?, ?, ?, NOW())"
                );
                $stmt->execute([$branchId, $medicineId, $quantity]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Reduce Stock
     * تقليل من المخزون
     */
    public function reduceStock($branchId, $medicineId, $quantity) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET quantity = quantity - ? 
                 WHERE branch_id = ? AND medicine_id = ? AND quantity >= ?"
            );
            return $stmt->execute([$quantity, $branchId, $medicineId, $quantity]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
