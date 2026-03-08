import type { App } from 'vue'
import httpClient from '@/core/api/httpClient'

export default {
  install(app: App) {
    app.config.globalProperties.$http = httpClient
  },
}
