import type { Metadata } from 'next'
import { Inter } from 'next/font/google'
import './globals.css'
import { Toaster } from 'react-hot-toast'
import Navbar from '@/components/Navbar'

const inter = Inter({ subsets: ['latin'] })

export const metadata: Metadata = {
  title: 'Mini Commerce',
  description: 'A simple e-commerce application',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body className={`${inter.className} bg-white dark:bg-gray-900 transition-colors`}>
        <Navbar />
        <main className="min-h-screen bg-gray-50 dark:bg-gray-800 transition-colors">
          {children}
        </main>
        <Toaster 
          position="top-right"
          toastOptions={{
            style: {
              background: 'var(--toast-bg)',
              color: 'var(--toast-color)',
            },
          }}
        />
      </body>
    </html>
  )
}
