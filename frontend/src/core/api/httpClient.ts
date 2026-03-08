import axios from 'axios'
import { setupInterceptors } from './interceptors'

const httpClient = axios.create({
  baseURL: import.meta.env.API_BASE_URL ?? 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

setupInterceptors(httpClient)

export default httpClient
