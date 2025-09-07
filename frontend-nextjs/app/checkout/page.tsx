'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import { cartAPI, orderAPI } from '@/lib/api'
import { toast } from 'react-hot-toast'
import LoadingSpinner from '@/components/LoadingSpinner'
import ProtectedRoute from '@/components/ProtectedRoute'

interface CartItem {
  id: string
  product_id: string
  product: {
    id: string
    name: string
    price: number
  }
  quantity: number
  subtotal: number
}

export default function CheckoutPage() {
  const [cartItems, setCartItems] = useState<CartItem[]>([])
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [formData, setFormData] = useState({
    shipping_address: '',
    shipping_city: '',
    shipping_postal_code: '',
    payment_method: 'credit_card',
  })
  const router = useRouter()

  useEffect(() => {
    fetchCart()
  }, [])

  const fetchCart = async () => {
    try {
      const response = await cartAPI.get()
      setCartItems(response.data.items || [])
    } catch (error) {
      console.error('Error fetching cart:', error)
    } finally {
      setLoading(false)
    }
  }

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    })
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setSubmitting(true)

    try {
      const orderData = {
        items: cartItems.map(item => ({
          product_id: item.product_id,
          quantity: item.quantity,
          price: item.product.price,
        })),
        shipping_address: formData.shipping_address,
        shipping_city: formData.shipping_city,
        shipping_postal_code: formData.shipping_postal_code,
        payment_method: formData.payment_method,
        total_amount: getTotalPrice(),
      }

      await orderAPI.create(orderData)
      await cartAPI.clear()
      
      toast.success('Order placed successfully!')
      router.push('/')
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to place order')
    } finally {
      setSubmitting(false)
    }
  }

  const getTotalPrice = () => {
    return cartItems.reduce((total, item) => total + item.subtotal, 0)
  }

  if (loading) {
    return <LoadingSpinner />
  }

  if (cartItems.length === 0) {
    return (
      <ProtectedRoute>
        <div className="container mx-auto px-4 py-8">
          <div className="text-center py-12">
            <p className="text-gray-500 text-lg mb-4">Your cart is empty</p>
            <button
              onClick={() => router.push('/')}
              className="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
            >
              Continue Shopping
            </button>
          </div>
        </div>
      </ProtectedRoute>
    )
  }

  return (
    <ProtectedRoute>
      <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

      <form onSubmit={handleSubmit} className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
          <div className="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h2 className="text-xl font-semibold mb-4">Shipping Information</h2>
            <div className="space-y-4">
              <div>
                <label htmlFor="shipping_address" className="block text-sm font-medium text-gray-700">
                  Address
                </label>
                <input
                  type="text"
                  id="shipping_address"
                  name="shipping_address"
                  required
                  className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                  value={formData.shipping_address}
                  onChange={handleChange}
                />
              </div>
              <div>
                <label htmlFor="shipping_city" className="block text-sm font-medium text-gray-700">
                  City
                </label>
                <input
                  type="text"
                  id="shipping_city"
                  name="shipping_city"
                  required
                  className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                  value={formData.shipping_city}
                  onChange={handleChange}
                />
              </div>
              <div>
                <label htmlFor="shipping_postal_code" className="block text-sm font-medium text-gray-700">
                  Postal Code
                </label>
                <input
                  type="text"
                  id="shipping_postal_code"
                  name="shipping_postal_code"
                  required
                  className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                  value={formData.shipping_postal_code}
                  onChange={handleChange}
                />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow-sm border p-6">
            <h2 className="text-xl font-semibold mb-4">Payment Method</h2>
            <select
              name="payment_method"
              value={formData.payment_method}
              onChange={handleChange}
              className="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="credit_card">Credit Card</option>
              <option value="debit_card">Debit Card</option>
              <option value="bank_transfer">Bank Transfer</option>
            </select>
          </div>
        </div>

        <div>
          <div className="bg-white rounded-lg shadow-sm border p-6">
            <h2 className="text-xl font-semibold mb-4">Order Summary</h2>
            <div className="space-y-2 mb-4">
              {cartItems.map((item) => (
                <div key={item.id} className="flex justify-between">
                  <span>{item.product.name} x {item.quantity}</span>
                  <span>${item.subtotal}</span>
                </div>
              ))}
            </div>
            <div className="border-t pt-2">
              <div className="flex justify-between text-lg font-semibold">
                <span>Total:</span>
                <span>${getTotalPrice()}</span>
              </div>
            </div>
            <button
              type="submit"
              disabled={submitting}
              className="w-full mt-6 bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors disabled:opacity-50"
            >
              {submitting ? 'Placing Order...' : 'Place Order'}
            </button>
          </div>
        </div>
      </form>
      </div>
    </ProtectedRoute>
  )
}
