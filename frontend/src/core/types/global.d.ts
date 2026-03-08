export {}

declare global {
  interface ApiResponse<T> {
    data: T
    message: string
    success: boolean
  }

  interface PaginatedResponse<T> {
    data: T[]
    total: number
    page: number
    per_page: number
    last_page: number
  }
}
