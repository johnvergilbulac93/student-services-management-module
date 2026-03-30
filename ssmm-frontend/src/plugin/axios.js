import axios from 'axios'
import router from '@/router'

const api = 'http://127.0.0.1:8000/backend/api/v1'

const axiosRequest = axios.create({
  baseURL: api,
  withCredentials: true,
  headers: {
    Accept: 'application/json',
  },
})

// Attach token dynamically
axiosRequest.interceptors.request.use((config) => {
  const token = localStorage.getItem('APP_TOKEN')

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

// Handle errors globally
axiosRequest.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('APP_TOKEN')
      router.push('/login')
    }

    return Promise.reject(error)
  },
)

export default axiosRequest
