'use client'

import { useEffect, useState } from 'react'
import { useRouter } from 'next/navigation'
import Cookies from 'js-cookie'
import LoadingSpinner from './LoadingSpinner'

interface ProtectedRouteProps {
  children: React.ReactNode
  redirectTo?: string
}

export default function ProtectedRoute({ 
  children, 
  redirectTo = '/login' 
}: ProtectedRouteProps) {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(null)
  const router = useRouter()

  useEffect(() => {
    const token = Cookies.get('token')
    
    if (!token) {
      setIsAuthenticated(false)
      router.push(redirectTo)
      return
    }

    // Token exists, consider user authenticated
    setIsAuthenticated(true)
  }, [router, redirectTo])

  // Show loading while checking authentication
  if (isAuthenticated === null) {
    return <LoadingSpinner />
  }

  // Show nothing if not authenticated (will redirect)
  if (!isAuthenticated) {
    return null
  }

  // Show protected content if authenticated
  return <>{children}</>
}
