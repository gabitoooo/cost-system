export interface User {
  id: number
  name: string
  email: string
  roles: string[]
}

export interface LoginRequest {
  email: string
  password: string
}

// El backend devuelve { data: { token, user } }
// El interceptor de axios quita el wrapper de axios,
// pero el { data: ... } del backend queda intacto
export interface LoginResponse {
  data: {
    token: string
    user: User
  }
}
