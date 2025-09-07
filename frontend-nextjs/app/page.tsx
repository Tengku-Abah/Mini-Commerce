'use client'

import { useEffect, useState } from 'react'
import { productAPI } from '@/lib/api'
import ProductCard from '@/components/ProductCard'
import LoadingSpinner from '@/components/LoadingSpinner'

interface Product {
  id: string
  name: string
  description: string
  price: number
  image_url: string
  stock: number
  category: string
}

export default function HomePage() {
  const [products, setProducts] = useState<Product[]>([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState('')

  useEffect(() => {
    fetchProducts()
  }, [])

  const fetchProducts = async () => {
    try {
      const response = await productAPI.getAll({ search: searchTerm })
      
      // Handle the correct response structure: response.data.data.data
      if (response.data && response.data.data && response.data.data.data && Array.isArray(response.data.data.data)) {
        setProducts(response.data.data.data)
      } else if (response.data && response.data.data && Array.isArray(response.data.data)) {
        setProducts(response.data.data)
      } else if (response.data && Array.isArray(response.data)) {
        setProducts(response.data)
      } else {
        setProducts([])
      }
    } catch (error) {
      console.error('Error fetching products:', error)
      setProducts([])
    } finally {
      setLoading(false)
    }
  }

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault()
    fetchProducts()
  }

  if (loading) {
    return <LoadingSpinner />
  }

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
          Welcome to Mini Commerce
        </h1>
        
        <form onSubmit={handleSearch} className="max-w-md">
          <div className="flex gap-2">
            <input
              type="text"
              placeholder="Search products..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
            <button
              type="submit"
              className="px-6 py-2 bg-primary-600 dark:bg-primary-500 text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors"
            >
              Search
            </button>
          </div>
        </form>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {Array.isArray(products) && products.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>

      {Array.isArray(products) && products.length === 0 && !loading && (
        <div className="text-center py-12">
          <p className="text-gray-500 dark:text-gray-400 text-lg">No products found</p>
        </div>
      )}
    </div>
  )
}
