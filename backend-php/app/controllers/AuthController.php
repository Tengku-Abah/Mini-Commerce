<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/JWT.php';
require_once __DIR__ . '/../helpers/Response.php';

class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            Response::validationError(['email' => 'Email is required', 'password' => 'Password is required']);
        }

        $email = $data['email'];
        $password = $data['password'];

        if (!$this->user->findByEmail($email)) {
            Response::error('Invalid credentials', 401);
        }

        if (!$this->user->verifyPassword($password)) {
            Response::error('Invalid credentials', 401);
        }

        $token = JWT::encode([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'role' => $this->user->role,
            'exp' => time() + (7 * 24 * 60 * 60) // 7 days
        ]);

        Response::success([
            'token' => $token,
            'user' => $this->user->toArray()
        ], 'Login successful');
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        $errors = [];
        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        if (!isset($data['email']) || empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if (!isset($data['password']) || empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            Response::validationError($errors);
        }

        // Check if email already exists
        if ($this->user->findByEmail($data['email'])) {
            Response::error('Email already exists', 409);
        }

        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->role = 'customer';

        if (!$this->user->create()) {
            Response::error('Registration failed', 500);
        }

        $token = JWT::encode([
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'role' => $this->user->role,
            'exp' => time() + (7 * 24 * 60 * 60) // 7 days
        ]);

        Response::success([
            'token' => $token,
            'user' => $this->user->toArray()
        ], 'Registration successful', 201);
    }

    public function logout() {
        // In a stateless JWT system, logout is handled on the client side
        // by removing the token. This endpoint is for consistency.
        Response::success(null, 'Logout successful');
    }

    public function me() {
        $user_data = JWT::getCurrentUser();
        
        if (!$user_data) {
            Response::unauthorized();
        }

        if (!$this->user->findById($user_data['user_id'])) {
            Response::unauthorized();
        }

        Response::success([
            'user' => $this->user->toArray()
        ]);
    }
}
?>
