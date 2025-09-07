'use client'

import { useState, useEffect } from 'react'
import { cartAPI } from '@/lib/api'
import Cookies from 'js-cookie'

export function useCart() {
  const [cartCount, setCartCount] = useState(0)
  const [loading, setLoading] = useState(false)

  const fetchCartCount = async () => {
    const token = Cookies.get('token')
    if (!token) {
      setCartCount(0)
      return
    }

    setLoading(true)
    try {
      const response = await cartAPI.get()
      const items = response.data.items || []
      const totalItems = items.reduce((sum: number, item: any) => sum + item.quantity, 0)
      setCartCount(totalItems)
    } catch (error) {
      setCartCount(0)
    } finally {
      setLoading(false)
    }
  }

  const addToCart = async (productId: string, quantity: number = 1) => {
    const token = Cookies.get('token')
    if (!token) return false

    try {
      await cartAPI.add(productId, quantity)
      await fetchCartCount() // Refresh cart count
      return true
    } catch (error) {
      return false
    }
  }

  const removeFromCart = async (productId: string) => {
    const token = Cookies.get('token')
    if (!token) return false

    try {
      await cartAPI.remove(productId)
      await fetchCartCount() // Refresh cart count
      return true
    } catch (error) {
      return false
    }
  }

  const updateQuantity = async (productId: string, quantity: number) => {
    const token = Cookies.get('token')
    if (!token) return false

    try {
      await cartAPI.update(productId, quantity)
      await fetchCartCount() // Refresh cart count
      return true
    } catch (error) {
      return false
    }
  }

  const clearCart = async () => {
    const token = Cookies.get('token')
    if (!token) return false

    try {
      await cartAPI.clear()
      await fetchCartCount() // Refresh cart count
      return true
    } catch (error) {
      return false
    }
  }

  useEffect(() => {
    fetchCartCount()
  }, [])

  return {
    cartCount,
    loading,
    fetchCartCount,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart
  }
}
