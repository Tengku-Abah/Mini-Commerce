import axios from 'axios';
import Cookies from 'js-cookie';

const API_BASE_URL = process.env.PHP_API_BASE || 'http://localhost:8000';

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = Cookies.get('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor to handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      Cookies.remove('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  login: (email: string, password: string) =>
    api.post('/auth/login', { email, password }),
  register: (userData: { name: string; email: string; password: string }) =>
    api.post('/auth/register', userData),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

// Product API
export const productAPI = {
  getAll: (params?: { page?: number; limit?: number; search?: string }) =>
    api.get('/products', { params }),
  getById: (id: string) => api.get(`/products/${id}`),
  create: (productData: FormData) => api.post('/products', productData),
  update: (id: string, productData: FormData) => api.put(`/products/${id}`, productData),
  delete: (id: string) => api.delete(`/products/${id}`),
};

// Cart API
export const cartAPI = {
  get: () => api.get('/cart'),
  add: (productId: string, quantity: number = 1) =>
    api.post('/cart/add', { product_id: productId, quantity }),
  update: (productId: string, quantity: number) =>
    api.put('/cart/update', { product_id: productId, quantity }),
  remove: (productId: string) => api.delete(`/cart/remove/${productId}`),
  clear: () => api.delete('/cart/clear'),
};

// Order API
export const orderAPI = {
  create: (orderData: any) => api.post('/orders', orderData),
  getAll: (params?: { page?: number; limit?: number }) =>
    api.get('/orders', { params }),
  getById: (id: string) => api.get(`/orders/${id}`),
  updateStatus: (id: string, status: string) =>
    api.put(`/orders/${id}/status`, { status }),
};

// Admin API
export const adminAPI = {
  getOrders: (params?: { page?: number; limit?: number; status?: string }) =>
    api.get('/admin/orders', { params }),
  getUsers: (params?: { page?: number; limit?: number }) =>
    api.get('/admin/users', { params }),
  updateOrderStatus: (id: string, status: string) =>
    api.put(`/admin/orders/${id}/status`, { status }),
};

export default api;
