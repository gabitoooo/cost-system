import { defineComponent, h } from 'vue'
import type { Component } from 'vue'

function icon(paths: string): Component {
  return defineComponent({
    render() {
      return h('svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        width: '20',
        height: '20',
        viewBox: '0 0 24 24',
        fill: 'none',
        stroke: 'currentColor',
        'stroke-width': '1.8',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        innerHTML: paths,
      })
    },
  })
}

// Dashboard / grid
export const GridIcon = icon(`
  <rect x="3" y="3" width="7" height="7" rx="1"/>
  <rect x="14" y="3" width="7" height="7" rx="1"/>
  <rect x="3" y="14" width="7" height="7" rx="1"/>
  <rect x="14" y="14" width="7" height="7" rx="1"/>
`)

// Configuración / engranaje
export const SettingsIcon = icon(`
  <circle cx="12" cy="12" r="3"/>
  <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33
    1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06
    a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09
    A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9
    4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06
    a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09
    a1.65 1.65 0 0 0-1.51 1z"/>
`)

// Recursos / caja 3D
export const BoxIcon = icon(`
  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4
    a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
  <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
  <line x1="12" y1="22.08" x2="12" y2="12"/>
`)

// Inductores / gráfico de barras
export const ChartBarIcon = icon(`
  <line x1="18" y1="20" x2="18" y2="10"/>
  <line x1="12" y1="20" x2="12" y2="4"/>
  <line x1="6"  y1="20" x2="6"  y2="14"/>
  <line x1="2"  y1="20" x2="22" y2="20"/>
`)

// Costos ABC / calculadora
export const CalculatorIcon = icon(`
  <rect x="4" y="2" width="16" height="20" rx="2"/>
  <line x1="8"  y1="6"  x2="16" y2="6"/>
  <line x1="8"  y1="10" x2="10" y2="10"/>
  <line x1="14" y1="10" x2="16" y2="10"/>
  <line x1="8"  y1="14" x2="10" y2="14"/>
  <line x1="14" y1="14" x2="16" y2="14"/>
  <line x1="8"  y1="18" x2="10" y2="18"/>
  <line x1="14" y1="18" x2="16" y2="18"/>
`)

// Administración / usuarios
export const UsersIcon = icon(`
  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
  <circle cx="9" cy="7" r="4"/>
  <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
  <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
`)

// Chevron abajo
export const ChevronDownIcon = icon(`
  <polyline points="6 9 12 15 18 9"/>
`)

// Tres puntos horizontales (sidebar colapsado)
export const HorizontalDots = icon(`
  <circle cx="5"  cy="12" r="1" fill="currentColor" stroke="none"/>
  <circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/>
  <circle cx="19" cy="12" r="1" fill="currentColor" stroke="none"/>
`)

// Luna — modo oscuro
export const MoonIcon = icon(`
  <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
`)

// Sol — modo claro
export const SunIcon = icon(`
  <circle cx="12" cy="12" r="5"/>
  <line x1="12" y1="1"  x2="12" y2="3"/>
  <line x1="12" y1="21" x2="12" y2="23"/>
  <line x1="4.22" y1="4.22"  x2="5.64"  y2="5.64"/>
  <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
  <line x1="1"  y1="12" x2="3"  y2="12"/>
  <line x1="21" y1="12" x2="23" y2="12"/>
  <line x1="4.22"  y1="19.78" x2="5.64"  y2="18.36"/>
  <line x1="18.36" y1="5.64"  x2="19.78" y2="4.22"/>
`)

// Campana — notificaciones
export const BellIcon = icon(`
  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
  <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
`)

// Cerrar sesión
export const LogoutIcon = icon(`
  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
  <polyline points="16 17 21 12 16 7"/>
  <line x1="21" y1="12" x2="9" y2="12"/>
`)
