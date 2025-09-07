<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/JWT.php';

class AdminController {
    private $order;
    private $user;

    public function __construct() {
        $this->order = new Order();
        $this->user = new User();
    }

    private function checkAdminAccess() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data || $user_data['role'] !== 'admin') {
            Response::forbidden('Admin access required');
        }
        
        return $user_data;
    }

    public function orders() {
        $this->checkAdminAccess();
        
        $params = $_GET;
        $orders = $this->order->getAll($params);
        
        Response::success([
            'data' => $orders,
            'total' => count($orders)
        ]);
    }

    public function users() {
        $this->checkAdminAccess();
        
        // This would need a User model method to get all users
        // For now, return empty array
        Response::success([
            'data' => [],
            'total' => 0
        ]);
    }

    public function updateOrderStatus($params) {
        $this->checkAdminAccess();
        
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
