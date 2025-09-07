/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['localhost', 'images.unsplash.com'],
  },
  env: {
    PHP_API_BASE: process.env.PHP_API_BASE || 'http://localhost:8000',
  },
}

module.exports = nextConfig
