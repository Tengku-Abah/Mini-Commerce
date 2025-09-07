<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/JWT.php';

class ProductController {
    private $product;

    public function __construct() {
        $this->product = new Product();
    }

    public function index() {
        $params = $_GET;
        $products = $this->product->getAll($params);
        
        Response::success([
            'data' => $products,
            'total' => count($products)
        ]);
    }

    public function show($params) {
        $id = $params[0];
        
        if (!$this->product->findById($id)) {
            Response::notFound('Product not found');
        }

        Response::success($this->product->toArray());
    }

    public function store() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data || $user_data['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $errors = [];
        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Valid price is required';
        }
        if (!isset($data['stock']) || !is_numeric($data['stock'])) {
            $errors['stock'] = 'Valid stock quantity is required';
        }

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        $this->product->name = $data['name'];
        $this->product->description = $data['description'] ?? '';
        $this->product->price = $data['price'];
        $this->product->image_url = $data['image_url'] ?? '';
        $this->product->stock = $data['stock'];
        $this->product->category = $data['category'] ?? '';

        if (!$this->product->create()) {
            Response::error('Failed to create product', 500);
        }

        Response::success($this->product->toArray(), 'Product created successfully', 201);
    }

    public function update($params) {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data || $user_data['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }

        $id = $params[0];
        
        if (!$this->product->findById($id)) {
            Response::notFound('Product not found');
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['name'])) $this->product->name = $data['name'];
        if (isset($data['description'])) $this->product->description = $data['description'];
        if (isset($data['price'])) $this->product->price = $data['price'];
        if (isset($data['image_url'])) $this->product->image_url = $data['image_url'];
        if (isset($data['stock'])) $this->product->stock = $data['stock'];
        if (isset($data['category'])) $this->product->category = $data['category'];

        if (!$this->product->update()) {
            Response::error('Failed to update product', 500);
        }

        Response::success($this->product->toArray(), 'Product updated successfully');
    }

    public function destroy($params) {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data || $user_data['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }

        $id = $params[0];
        
        if (!$this->product->findById($id)) {
            Response::notFound('Product not found');
        }

        if (!$this->product->delete()) {
            Response::error('Failed to delete product', 500);
        }

        Response::success(null, 'Product deleted successfully');
    }
}
?>
