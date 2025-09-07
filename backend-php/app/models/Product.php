<?php
require_once __DIR__ . '/../../config/db.php';

class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $image_url;
    public $stock;
    public $category;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll($params = []) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        $params_array = [];

        if (isset($params['search']) && !empty($params['search'])) {
            $query .= " AND (name LIKE :search OR description LIKE :search)";
            $params_array[':search'] = '%' . $params['search'] . '%';
        }

        if (isset($params['category']) && !empty($params['category'])) {
            $query .= " AND category = :category";
            $params_array[':category'] = $params['category'];
        }

        $query .= " ORDER BY created_at DESC";

        if (isset($params['limit'])) {
            $query .= " LIMIT :limit";
            $params_array[':limit'] = (int)$params['limit'];
        }

        if (isset($params['offset'])) {
            $query .= " OFFSET :offset";
            $params_array[':offset'] = (int)$params['offset'];
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params_array as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->image_url = $row['image_url'];
            $this->stock = $row['stock'];
            $this->category = $row['category'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, description=:description, price=:price, 
                      image_url=:image_url, stock=:stock, category=:category";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = (float)$this->price;
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->stock = (int)$this->stock;
        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category", $this->category);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, description=:description, price=:price, 
                      image_url=:image_url, stock=:stock, category=:category
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = (float)$this->price;
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->stock = (int)$this->stock;
        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'stock' => $this->stock,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
?>
