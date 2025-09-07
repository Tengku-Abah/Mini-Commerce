<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/JWT.php';

class OrderController {
    private $order;
    private $cart;

    public function __construct() {
        $this->order = new Order();
        $this->cart = new Cart();
    }

    public function index() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $params = $_GET;
        $orders = $this->order->getByUserId($user_data['user_id'], $params);
        
        Response::success([
            'data' => $orders,
            'total' => count($orders)
        ]);
    }

    public function show($params) {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $id = $params[0];
        
        if (!$this->order->findById($id)) {
            Response::notFound('Order not found');
        }

        // Check if user owns this order or is admin
        if ($this->order->user_id != $user_data['user_id'] && $user_data['role'] !== 'admin') {
            Response::forbidden();
        }

        Response::success($this->order->toArray());
    }

    public function store() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $errors = [];
        if (!isset($data['items']) || empty($data['items'])) {
            $errors['items'] = 'Order items are required';
        }
        if (!isset($data['shipping_address']) || empty($data['shipping_address'])) {
            $errors['shipping_address'] = 'Shipping address is required';
        }
        if (!isset($data['shipping_city']) || empty($data['shipping_city'])) {
            $errors['shipping_city'] = 'Shipping city is required';
        }
        if (!isset($data['shipping_postal_code']) || empty($data['shipping_postal_code'])) {
            $errors['shipping_postal_code'] = 'Shipping postal code is required';
        }
        if (!isset($data['payment_method']) || empty($data['payment_method'])) {
            $errors['payment_method'] = 'Payment method is required';
        }

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        $order_data = [
            'items' => $data['items'],
            'total_amount' => $data['total_amount'],
            'status' => 'pending',
            'shipping_address' => $data['shipping_address'],
            'shipping_city' => $data['shipping_city'],
            'shipping_postal_code' => $data['shipping_postal_code'],
            'payment_method' => $data['payment_method']
        ];

        if (!$this->order->create($user_data['user_id'], $order_data)) {
            Response::error('Failed to create order', 500);
        }

        // Clear cart after successful order
        $this->cart->clearCart($user_data['user_id']);

        Response::success($this->order->toArray(), 'Order created successfully', 201);
    }

    public function updateStatus($params) {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data || $user_data['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }

        $id = $params[0];
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['status'])) {
            Response::validationError(['status' => 'Status is required']);
        }

        if (!$this->order->findById($id)) {
            Response::notFound('Order not found');
        }

        if (!$this->order->updateStatus($id, $data['status'])) {
            Response::error('Failed to update order status', 500);
        }

        Response::success($this->order->toArray(), 'Order status updated');
    }
}
?>
