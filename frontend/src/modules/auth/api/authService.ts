import { http } from '@/app/api/http'
import type { LoginRequest, LoginResponse, User } from '../types'

const authService = {
  login(credentials: LoginRequest): Promise<LoginResponse> {
    return http.post('/auth/login', credentials)
  },

  logout(): Promise<void> {
    return http.post('/auth/logout')
  },

  me(): Promise<{ data: { user: User } }> {
    return http.get('/auth/me')
  },
}

export default authService
