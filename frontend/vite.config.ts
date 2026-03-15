import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import Components from 'unplugin-vue-components/vite'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig({
  plugins: [
    tailwindcss(),
    vue(),
    Components({
      dirs: ['src/components/ui'],
      dts: 'src/components.d.ts',
      resolvers: [
        // Auto-importa cualquier XxxIcon desde @/components/icons
        (name) => {
          if (name.endsWith('Icon')) {
            return { name, from: '@/components/icons' }
          }
        },
      ],
    }),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://web:80',
        changeOrigin: true,
      },
    },
  },
})
