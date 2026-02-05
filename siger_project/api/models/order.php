<?php
/**
 * Modelo Order - Gestión de pedidos
 * Maneja los pedidos de clientes (tablet y web)
 */

require_once __DIR__ . '/../../config/db.php';

class Order {
    private $db;
    private $table = 'orders';
    private $detailsTable = 'order_details';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Crear nueva orden con sus detalles
     */
    public function create($orderData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Insertar orden principal
            $query = "INSERT INTO {$this->table} 
                      (table_number, customer_name, total, status, order_type, notes) 
                      VALUES (:table_number, :customer_name, :total, :status, :order_type, :notes)";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':table_number', $orderData['table_number']);
            $stmt->bindParam(':customer_name', $orderData['customer_name']);
            $stmt->bindParam(':total', $orderData['total']);
            $stmt->bindParam(':status', $orderData['status']);
            $stmt->bindParam(':order_type', $orderData['order_type']);
            $stmt->bindParam(':notes', $orderData['notes']);
            
            $stmt->execute();
            $orderId = $this->db->lastInsertId();
            
            // Insertar detalles de la orden
            $detailQuery = "INSERT INTO {$this->detailsTable} 
                           (order_id, product_id, product_name, quantity, price, subtotal) 
                           VALUES (:order_id, :product_id, :product_name, :quantity, :price, :subtotal)";
            
            $detailStmt = $this->db->prepare($detailQuery);
            
            foreach ($items as $item) {
                $detailStmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':product_name' => $item['product_name'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':subtotal' => $item['subtotal']
                ]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Obtener todas las órdenes con filtros
     */
    public function getAll($filters = []) {
        $query = "SELECT o.*, 
                  COUNT(od.id) as items_count
                  FROM {$this->table} o
                  LEFT JOIN {$this->detailsTable} od ON o.id = od.order_id
                  WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['status'])) {
            $query .= " AND o.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (isset($filters['order_type'])) {
            $query .= " AND o.order_type = :order_type";
            $params[':order_type'] = $filters['order_type'];
        }
        
        if (isset($filters['date'])) {
            $query .= " AND DATE(o.created_at) = :date";
            $params[':date'] = $filters['date'];
        }
        
        $query .= " GROUP BY o.id ORDER BY o.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener orden por ID con sus detalles
     */
    public function getById($id) {
        // Obtener datos de la orden
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $order = $stmt->fetch();
        
        if (!$order) {
            return null;
        }
        
        // Obtener detalles
        $detailQuery = "SELECT * FROM {$this->detailsTable} WHERE order_id = :order_id";
        $detailStmt = $this->db->prepare($detailQuery);
        $detailStmt->bindParam(':order_id', $id);
        $detailStmt->execute();
        
        $order['items'] = $detailStmt->fetchAll();
        
        return $order;
    }
    
    /**
     * Actualizar estado de la orden
     */
    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Obtener órdenes activas para la cocina
     */
    public function getActiveOrders() {
        $query = "SELECT o.*, 
                  GROUP_CONCAT(
                      CONCAT(od.quantity, 'x ', od.product_name) 
                      SEPARATOR ', '
                  ) as items_summary
                  FROM {$this->table} o
                  LEFT JOIN {$this->detailsTable} od ON o.id = od.order_id
                  WHERE o.status IN ('pending', 'preparing')
                  GROUP BY o.id
                  ORDER BY o.created_at ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de órdenes
     */
    public function getStats($startDate, $endDate) {
        $query = "SELECT 
                  COUNT(*) as total_orders,
                  SUM(total) as total_sales,
                  AVG(total) as average_order,
                  COUNT(CASE WHEN status = 'delivered' THEN 1 END) as completed_orders,
                  COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled_orders
                  FROM {$this->table}
                  WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Obtener productos más vendidos
     */
    public function getTopProducts($limit = 10) {
        $query = "SELECT 
                  product_id,
                  product_name,
                  SUM(quantity) as total_quantity,
                  SUM(subtotal) as total_revenue
                  FROM {$this->detailsTable}
                  GROUP BY product_id, product_name
                  ORDER BY total_quantity DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
?>
