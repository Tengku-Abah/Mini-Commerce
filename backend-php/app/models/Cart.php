<?php
require_once __DIR__ . '/../../config/db.php';

class Cart {
    private $conn;
    private $table_name = "cart_items";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getCartItems($user_id) {
        $query = "SELECT ci.*, p.name as product_name, p.price, p.image_url 
                  FROM " . $this->table_name . " ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItem($user_id, $product_id, $quantity = 1) {
        // Check if item already exists in cart
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_item) {
            // Update quantity
            $new_quantity = $existing_item['quantity'] + $quantity;
            $query = "UPDATE " . $this->table_name . " 
                      SET quantity = :quantity 
                      WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quantity', $new_quantity);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            return $stmt->execute();
        } else {
            // Add new item
            $query = "INSERT INTO " . $this->table_name . " 
                      SET user_id = :user_id, product_id = :product_id, quantity = :quantity";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($user_id, $product_id);
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET quantity = :quantity 
                  WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function removeItem($user_id, $product_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product_id', $product_id);
        return $stmt->execute();
    }

    public function clearCart($user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>
