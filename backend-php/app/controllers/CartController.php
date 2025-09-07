<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/JWT.php';

class CartController {
    private $cart;

    public function __construct() {
        $this->cart = new Cart();
    }

    public function index() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $cart_items = $this->cart->getCartItems($user_data['user_id']);
        
        // Calculate subtotals
        $items = array_map(function($item) {
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        }, $cart_items);

        Response::success([
            'items' => $items,
            'total' => array_sum(array_column($items, 'subtotal'))
        ]);
    }

    public function add() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['product_id'])) {
            Response::validationError(['product_id' => 'Product ID is required']);
        }

        $product_id = $data['product_id'];
        $quantity = $data['quantity'] ?? 1;

        if (!$this->cart->addItem($user_data['user_id'], $product_id, $quantity)) {
            Response::error('Failed to add item to cart', 500);
        }

        Response::success(null, 'Item added to cart');
    }

    public function update() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            Response::validationError([
                'product_id' => 'Product ID is required',
                'quantity' => 'Quantity is required'
            ]);
        }

        $product_id = $data['product_id'];
        $quantity = (int)$data['quantity'];

        if (!$this->cart->updateQuantity($user_data['user_id'], $product_id, $quantity)) {
            Response::error('Failed to update cart', 500);
        }

        Response::success(null, 'Cart updated');
    }

    public function remove($params) {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        $product_id = $params[0];

        if (!$this->cart->removeItem($user_data['user_id'], $product_id)) {
            Response::error('Failed to remove item from cart', 500);
        }

        Response::success(null, 'Item removed from cart');
    }

    public function clear() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        if (!$this->cart->clearCart($user_data['user_id'])) {
            Response::error('Failed to clear cart', 500);
        }

        Response::success(null, 'Cart cleared');
    }
}
?>
