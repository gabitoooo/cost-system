<template>
  <div class="relative flex min-h-screen bg-white dark:bg-gray-900">

    <!-- Panel izquierdo — formulario -->
    <div class="flex flex-col flex-1 w-full lg:w-1/2">

      <!-- Cabecera con logo (móvil) -->
      <div class="flex items-center gap-3 px-6 pt-8 lg:hidden">
        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-brand-500 shrink-0">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6"  y1="20" x2="6"  y2="14"/>
            <line x1="2"  y1="20" x2="22" y2="20"/>
          </svg>
        </div>
        <span class="font-semibold text-gray-800 dark:text-white">Sistema de Costos</span>
      </div>

      <!-- Formulario centrado -->
      <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto px-6 py-12">
        <div>
          <!-- Título -->
          <div class="mb-8">
            <h1 class="mb-2 font-semibold text-gray-800 dark:text-white/90 text-2xl lg:text-3xl">
              Iniciar Sesión
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Ingresa tus credenciales para acceder al sistema de costos.
            </p>
          </div>

          <!-- Formulario -->
          <form @submit.prevent="handleSubmit" class="space-y-5">

            <!-- Email -->
            <div>
              <label
                for="email"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
              >
                Correo electrónico <span class="text-error-500">*</span>
              </label>
              <input
                v-model="email"
                type="email"
                id="email"
                placeholder="usuario@empresa.com"
                autocomplete="email"
                class="h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent
                       px-4 py-2.5 text-sm text-gray-800 dark:text-white/90
                       placeholder:text-gray-400 dark:placeholder:text-white/30
                       focus:border-brand-300 dark:focus:border-brand-700
                       focus:outline-none focus:ring-2 focus:ring-brand-500/10
                       dark:bg-gray-900/50 transition-colors"
              />
              <p v-if="getFieldError(validationErrors, 'email')" class="mt-1 text-xs text-error-500">
                {{ getFieldError(validationErrors, 'email') }}
              </p>
            </div>

            <!-- Contraseña -->
            <div>
              <label
                for="password"
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
              >
                Contraseña <span class="text-error-500">*</span>
              </label>
              <div class="relative">
                <input
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  id="password"
                  placeholder="••••••••"
                  autocomplete="current-password"
                  class="h-11 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent
                         py-2.5 pl-4 pr-11 text-sm text-gray-800 dark:text-white/90
                         placeholder:text-gray-400 dark:placeholder:text-white/30
                         focus:border-brand-300 dark:focus:border-brand-700
                         focus:outline-none focus:ring-2 focus:ring-brand-500/10
                         dark:bg-gray-900/50 transition-colors"
                />
              <p v-if="getFieldError(validationErrors, 'password')" class="mt-1 text-xs text-error-500">
                  {{ getFieldError(validationErrors, 'password') }}
                </p>
                <!-- Toggle contraseña -->
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600
                         dark:text-gray-500 dark:hover:text-gray-300 transition-colors"
                >
                  <!-- Ojo abierto -->
                  <svg v-if="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  <!-- Ojo cerrado -->
                  <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Recordar + Olvidé contraseña -->
            <div class="flex items-center justify-between">
              <label class="flex items-center gap-2 cursor-pointer select-none text-sm text-gray-600 dark:text-gray-400">
                <div class="relative">
                  <input v-model="keepLoggedIn" type="checkbox" class="sr-only" />
                  <div
                    class="w-5 h-5 rounded flex items-center justify-center border-[1.5px] transition-colors"
                    :class="keepLoggedIn
                      ? 'bg-brand-500 border-brand-500'
                      : 'bg-transparent border-gray-300 dark:border-gray-600'"
                  >
                    <svg v-if="keepLoggedIn" width="11" height="9" viewBox="0 0 14 11" fill="none">
                      <path d="M1 5.5L5 9.5L13 1.5" stroke="white" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </div>
                Mantener sesión activa
              </label>
              <a href="#" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400">
                ¿Olvidaste tu contraseña?
              </a>
            </div>

            <!-- Botón submit -->
            <button
              type="submit"
              :disabled="loading"
              class="flex items-center justify-center w-full h-11 px-4 rounded-lg
                     bg-brand-500 hover:bg-brand-600 active:bg-brand-700
                     text-white text-sm font-medium
                     shadow-theme-xs transition-colors
                     disabled:opacity-60 disabled:cursor-not-allowed"
            >
              {{ loading ? 'Ingresando...' : 'Ingresar' }}
            </button>
          </form>
        </div>
      </div>
    </div>

    

  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/modules/auth/store/authStore'
import { useApi } from '@/composables/useApi'
import { getFieldError } from '@/app/api/types'

const router = useRouter()
const authStore = useAuthStore()
const { execute, loading, validationErrors } = useApi()

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const keepLoggedIn = ref(false)

const handleSubmit = async () => {
  const result = await execute(() =>
    authStore.login({ email: email.value, password: password.value }),
  )
  if (result !== null) router.push('/dashboard')
}
</script>
