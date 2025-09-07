'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import { toast } from 'react-hot-toast'
import Cookies from 'js-cookie'
import { useCart } from '@/hooks/useCart'

interface Product {
  id: string
  name: string
  description: string
  price: number
  image_url: string
  stock: number
  category: string
}

interface ProductCardProps {
  product: Product
}

export default function ProductCard({ product }: ProductCardProps) {
  const [addingToCart, setAddingToCart] = useState(false)
  const router = useRouter()
  const { addToCart } = useCart()

  const handleAddToCart = async () => {
    const token = Cookies.get('token')
    
    if (!token) {
      toast.error('Please login to add items to cart')
      router.push('/login')
      return
    }

    setAddingToCart(true)
    try {
      const success = await addToCart(product.id, 1)
      if (success) {
        toast.success('Added to cart!')
      } else {
        toast.error('Failed to add to cart')
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to add to cart')
    } finally {
      setAddingToCart(false)
    }
  }

  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
      <div className="aspect-w-16 aspect-h-9">
        <img
          src={product.image_url}
          alt={product.name}
          className="w-full h-48 object-cover"
        />
      </div>
      <div className="p-4">
        <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
          {product.name}
        </h3>
        <p className="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2">
          {product.description}
        </p>
        <div className="flex items-center justify-between mb-3">
          <span className="text-2xl font-bold text-primary-600 dark:text-primary-400">
            ${product.price}
          </span>
          <span className="text-sm text-gray-500 dark:text-gray-400">
            Stock: {product.stock}
          </span>
        </div>
        <div className="flex items-center justify-between">
          <span className="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
            {product.category}
          </span>
          <button
            onClick={handleAddToCart}
            disabled={addingToCart || product.stock === 0}
            className="bg-primary-600 dark:bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {addingToCart ? 'Adding...' : 'Add to Cart'}
          </button>
        </div>
      </div>
    </div>
  )
}
