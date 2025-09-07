<?php
require_once __DIR__ . '/../../config/db.php';

class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $user_id;
    public $total_amount;
    public $status;
    public $shipping_address;
    public $shipping_city;
    public $shipping_postal_code;
    public $payment_method;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($user_id, $order_data) {
        $this->conn->beginTransaction();

        try {
            // Create order
            $query = "INSERT INTO " . $this->table_name . " 
                      SET user_id=:user_id, total_amount=:total_amount, status=:status,
                          shipping_address=:shipping_address, shipping_city=:shipping_city,
                          shipping_postal_code=:shipping_postal_code, payment_method=:payment_method";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":total_amount", $order_data['total_amount']);
            $stmt->bindParam(":status", $order_data['status'] ?? 'pending');
            $stmt->bindParam(":shipping_address", $order_data['shipping_address']);
            $stmt->bindParam(":shipping_city", $order_data['shipping_city']);
            $stmt->bindParam(":shipping_postal_code", $order_data['shipping_postal_code']);
            $stmt->bindParam(":payment_method", $order_data['payment_method']);

            if (!$stmt->execute()) {
                throw new Exception('Failed to create order');
            }

            $order_id = $this->conn->lastInsertId();

            // Create order items
            foreach ($order_data['items'] as $item) {
                $query = "INSERT INTO order_items 
                          SET order_id=:order_id, product_id=:product_id, 
                              quantity=:quantity, price=:price";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":order_id", $order_id);
                $stmt->bindParam(":product_id", $item['product_id']);
                $stmt->bindParam(":quantity", $item['quantity']);
                $stmt->bindParam(":price", $item['price']);

                if (!$stmt->execute()) {
                    throw new Exception('Failed to create order items');
                }
            }

            $this->conn->commit();
            $this->id = $order_id;
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getByUserId($user_id, $params = []) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC";

        if (isset($params['limit'])) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if (isset($params['limit'])) {
            $stmt->bindParam(':limit', (int)$params['limit'], PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($params = []) {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                  FROM " . $this->table_name . " o
                  JOIN users u ON o.user_id = u.id
                  WHERE 1=1";

        if (isset($params['status']) && !empty($params['status'])) {
            $query .= " AND o.status = :status";
        }

        $query .= " ORDER BY o.created_at DESC";

        if (isset($params['limit'])) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);
        
        if (isset($params['status'])) {
            $stmt->bindParam(':status', $params['status']);
        }
        
        if (isset($params['limit'])) {
            $stmt->bindParam(':limit', (int)$params['limit'], PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT o.*, u.name as user_name, u.email as user_email 
                  FROM " . $this->table_name . " o
                  JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->user_id = $row['user_id'];
            $this->total_amount = $row['total_amount'];
            $this->status = $row['status'];
            $this->shipping_address = $row['shipping_address'];
            $this->shipping_city = $row['shipping_city'];
            $this->shipping_postal_code = $row['shipping_postal_code'];
            $this->payment_method = $row['payment_method'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " 
                  SET status = :status, updated_at = NOW()
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'shipping_city' => $this->shipping_city,
            'shipping_postal_code' => $this->shipping_postal_code,
            'payment_method' => $this->payment_method,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
?>
