'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'
import { cartAPI } from '@/lib/api'
import { toast } from 'react-hot-toast'
import LoadingSpinner from '@/components/LoadingSpinner'
import ProtectedRoute from '@/components/ProtectedRoute'
import { useCart } from '@/hooks/useCart'

interface CartItem {
  id: string
  product_id: string
  product: {
    id: string
    name: string
    price: number
    image_url: string
  }
  quantity: number
  subtotal: number
}

export default function CartPage() {
  const [cartItems, setCartItems] = useState<CartItem[]>([])
  const [loading, setLoading] = useState(true)
  const [updating, setUpdating] = useState<string | null>(null)
  const router = useRouter()
  const { fetchCartCount } = useCart()

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

  const updateQuantity = async (productId: string, newQuantity: number) => {
    if (newQuantity < 1) return

    setUpdating(productId)
    try {
      await cartAPI.update(productId, newQuantity)
      await fetchCart()
      await fetchCartCount() // Update cart count in navbar
      toast.success('Cart updated')
    } catch (error) {
      toast.error('Failed to update cart')
    } finally {
      setUpdating(null)
    }
  }

  const removeItem = async (productId: string) => {
    try {
      await cartAPI.remove(productId)
      await fetchCart()
      await fetchCartCount() // Update cart count in navbar
      toast.success('Item removed from cart')
    } catch (error) {
      toast.error('Failed to remove item')
    }
  }

  const getTotalPrice = () => {
    return cartItems.reduce((total, item) => total + item.subtotal, 0)
  }

  if (loading) {
    return <LoadingSpinner />
  }

  return (
    <ProtectedRoute>
      <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

      {cartItems.length === 0 ? (
        <div className="text-center py-12">
          <p className="text-gray-500 text-lg mb-4">Your cart is empty</p>
          <Link
            href="/"
            className="inline-block px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
          >
            Continue Shopping
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2">
            <div className="space-y-4">
              {cartItems.map((item) => (
                <div
                  key={item.id}
                  className="bg-white rounded-lg shadow-sm border p-6 flex items-center space-x-4"
                >
                  <img
                    src={item.product.image_url}
                    alt={item.product.name}
                    className="w-20 h-20 object-cover rounded-lg"
                  />
                  <div className="flex-1">
                    <h3 className="text-lg font-semibold text-gray-900">
                      {item.product.name}
                    </h3>
                    <p className="text-gray-600">${item.product.price}</p>
                  </div>
                  <div className="flex items-center space-x-2">
                    <button
                      onClick={() => updateQuantity(item.product_id, item.quantity - 1)}
                      disabled={updating === item.product_id}
                      className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
                    >
                      -
                    </button>
                    <span className="w-8 text-center">{item.quantity}</span>
                    <button
                      onClick={() => updateQuantity(item.product_id, item.quantity + 1)}
                      disabled={updating === item.product_id}
                      className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 disabled:opacity-50"
                    >
                      +
                    </button>
                  </div>
                  <div className="text-right">
                    <p className="text-lg font-semibold">${item.subtotal}</p>
                    <button
                      onClick={() => removeItem(item.product_id)}
                      className="text-red-600 hover:text-red-800 text-sm"
                    >
                      Remove
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-sm border p-6">
              <h2 className="text-xl font-semibold mb-4">Order Summary</h2>
              <div className="space-y-2 mb-4">
                <div className="flex justify-between">
                  <span>Subtotal:</span>
                  <span>${getTotalPrice()}</span>
                </div>
                <div className="flex justify-between">
                  <span>Shipping:</span>
                  <span>Free</span>
                </div>
                <div className="border-t pt-2">
                  <div className="flex justify-between text-lg font-semibold">
                    <span>Total:</span>
                    <span>${getTotalPrice()}</span>
                  </div>
                </div>
              </div>
              <button
                onClick={() => router.push('/checkout')}
                className="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-colors"
              >
                Proceed to Checkout
              </button>
            </div>
          </div>
        </div>
      )}
      </div>
    </ProtectedRoute>
  )
}
