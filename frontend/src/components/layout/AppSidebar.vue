<template>
  <aside
    :class="[
      'fixed top-0 left-0 z-99999 flex flex-col h-screen px-5',
      'bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800',
      'transition-all duration-300 ease-in-out',
      // Desktop: colapsa a 90px o expande a 290px
      isExpanded || isHovered ? 'lg:w-[290px]' : 'lg:w-[90px]',
      // Mobile: se desliza desde la izquierda
      isMobileOpen ? 'translate-x-0 w-[290px]' : '-translate-x-full',
      'lg:translate-x-0',
      // Offset en mobile para que no tape el header
      'mt-16 lg:mt-0',
    ]"
    @mouseenter="!isExpanded && (isHovered = true)"
    @mouseleave="isHovered = false"
  >
    <!-- Logo -->
    <div
      class="py-7 flex shrink-0"
      :class="isExpanded || isHovered || isMobileOpen ? 'justify-start' : 'lg:justify-center'"
    >
      <router-link to="/dashboard" class="flex items-center gap-3 overflow-hidden">
        <!-- Icono siempre visible -->
        <div class="shrink-0 flex items-center justify-center w-9 h-9 rounded-lg bg-brand-500">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6"  y1="20" x2="6"  y2="14"/>
            <line x1="2"  y1="20" x2="22" y2="20"/>
          </svg>
        </div>
        <!-- Nombre solo cuando está expandido -->
        <span
          v-if="isExpanded || isHovered || isMobileOpen"
          class="text-base font-semibold text-gray-800 dark:text-white whitespace-nowrap"
        >
          Sistema de Costos
        </span>
      </router-link>
    </div>

    <!-- Navegación -->
    <nav class="flex flex-col overflow-y-auto no-scrollbar gap-5 flex-1 pb-6">
      <div v-for="(group, gi) in menuGroups" :key="gi">
        <!-- Título del grupo -->
        <h2
          class="mb-3 text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 flex leading-5"
          :class="!isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start'"
        >
          <template v-if="isExpanded || isHovered || isMobileOpen">
            {{ group.title }}
          </template>
          <component :is="HorizontalDots" v-else />
        </h2>

        <!-- Items -->
        <ul class="flex flex-col gap-1">
          <li v-for="(item, ii) in group.items" :key="ii">

            <!-- Item con subitems -->
            <button
              v-if="item.subItems"
              @click="toggleSubmenu(`${gi}-${ii}`)"
              class="menu-item group w-full"
              :class="[
                isSubmenuOpen(gi, ii) ? 'menu-item-active' : 'menu-item-inactive',
                !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start',
              ]"
            >
              <span :class="isSubmenuOpen(gi, ii) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                <component :is="item.icon" />
              </span>
              <span v-if="isExpanded || isHovered || isMobileOpen" class="flex-1 text-left">
                {{ item.name }}
              </span>
              <component
                :is="ChevronDownIcon"
                v-if="isExpanded || isHovered || isMobileOpen"
                class="ml-auto w-4 h-4 transition-transform duration-200"
                :class="isSubmenuOpen(gi, ii) ? 'rotate-180 text-brand-500' : ''"
              />
            </button>

            <!-- Item con ruta directa -->
            <router-link
              v-else-if="item.path"
              :to="item.path"
              class="menu-item group"
              :class="[
                isActive(item.path) ? 'menu-item-active' : 'menu-item-inactive',
                !isExpanded && !isHovered ? 'lg:justify-center' : '',
              ]"
            >
              <span :class="isActive(item.path) ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                <component :is="item.icon" />
              </span>
              <span v-if="isExpanded || isHovered || isMobileOpen">{{ item.name }}</span>
            </router-link>

            <!-- Subitems (con animación) -->
            <transition
              @enter="startTransition"
              @after-enter="endTransition"
              @before-leave="startTransition"
              @after-leave="endTransition"
            >
              <div v-show="isSubmenuOpen(gi, ii) && (isExpanded || isHovered || isMobileOpen)">
                <ul class="mt-1 space-y-0.5 ml-8">
                  <li v-for="sub in item.subItems" :key="sub.name">
                    <router-link
                      :to="sub.path"
                      class="menu-dropdown-item"
                      :class="isActive(sub.path) ? 'menu-dropdown-item-active' : 'menu-dropdown-item-inactive'"
                    >
                      {{ sub.name }}
                    </router-link>
                  </li>
                </ul>
              </div>
            </transition>

          </li>
        </ul>
      </div>
    </nav>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useSidebar } from '@/composables/useSidebar'
import {
  GridIcon,
  SettingsIcon,
  BoxIcon,
  ChartBarIcon,
  CalculatorIcon,
  UsersIcon,
  ChevronDownIcon,
  HorizontalDots,
} from '@/components/icons'

const route = useRoute()
const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar()

// ─── Menú del sistema ───────────────────────────────────────────────────────
const menuGroups = [
  {
    title: 'Menú',
    items: [
      { icon: GridIcon, name: 'Dashboard', path: '/dashboard' },
    ],
  },
  {
    title: 'Módulos',
    items: [
      {
        icon: SettingsIcon,
        name: 'Configuración',
        subItems: [
          { name: 'Empresa',        path: '/empresa' },
          { name: 'Departamentos',  path: '/departamentos' },
          { name: 'Actividades',    path: '/actividades' },
        ],
      },
      {
        icon: BoxIcon,
        name: 'Recursos',
        subItems: [
          { name: 'Centros de Costo',     path: '/centros-costo' },
          { name: 'Recursos Productivos', path: '/recursos' },
        ],
      },
      {
        icon: ChartBarIcon,
        name: 'Inductores',
        subItems: [
          { name: 'Inductores de Recursos',    path: '/inductores-recursos' },
          { name: 'Inductores de Actividades', path: '/inductores-actividades' },
        ],
      },
      {
        icon: CalculatorIcon,
        name: 'Costos ABC',
        subItems: [
          { name: 'Asignación', path: '/asignacion' },
          { name: 'Reportes',   path: '/reportes' },
        ],
      },
    ],
  },
  {
    title: 'Sistema',
    items: [
      {
        icon: UsersIcon,
        name: 'Administración',
        subItems: [
          { name: 'Usuarios',      path: '/usuarios' },
          { name: 'Configuración', path: '/configuracion' },
        ],
      },
    ],
  },
]

// ─── Helpers ─────────────────────────────────────────────────────────────────
const isActive = (path: string) => route.path === path

const toggleSubmenu = (key: string) => {
  openSubmenu.value = openSubmenu.value === key ? null : key
}

const isSubmenuOpen = (gi: number, ii: number) => {
  const key = `${gi}-${ii}`
  if (openSubmenu.value === key) return true
  // Auto-abrir si un subitem está activo
  const item = menuGroups[gi]?.items[ii]
  return item?.subItems?.some((s) => isActive(s.path)) ?? false
}

// ─── Animación altura subitems ────────────────────────────────────────────────
const startTransition = (el: Element) => {
  const htmlEl = el as HTMLElement
  htmlEl.style.height = 'auto'
  const h = htmlEl.scrollHeight
  htmlEl.style.height = '0px'
  void htmlEl.offsetHeight
  htmlEl.style.height = h + 'px'
}
const endTransition = (el: Element) => {
  ;(el as HTMLElement).style.height = ''
}
</script>
