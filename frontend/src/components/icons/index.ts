import { defineComponent, h } from 'vue'
import type { Component } from 'vue'

// El tamaño lo controla la clase Tailwind (h-4 w-4, h-5 w-5, etc.)
function icon(paths: string): Component {
  return defineComponent({
    render() {
      return h('svg', {
        xmlns: 'http://www.w3.org/2000/svg',
        viewBox: '0 0 24 24',
        fill: 'none',
        stroke: 'currentColor',
        'stroke-width': '2',
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        innerHTML: paths,
      })
    },
  })
}

// ----- Layout / sidebar -----
export const GridIcon = icon(`
  <rect x="3" y="3" width="7" height="7" rx="1"/>
  <rect x="14" y="3" width="7" height="7" rx="1"/>
  <rect x="3" y="14" width="7" height="7" rx="1"/>
  <rect x="14" y="14" width="7" height="7" rx="1"/>
`)

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

export const ChevronDownIcon = icon(`<polyline points="6 9 12 15 18 9"/>`)

export const HorizontalDots = icon(`
  <circle cx="5"  cy="12" r="1" fill="currentColor" stroke="none"/>
  <circle cx="12" cy="12" r="1" fill="currentColor" stroke="none"/>
  <circle cx="19" cy="12" r="1" fill="currentColor" stroke="none"/>
`)

export const MoonIcon = icon(`<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>`)

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

export const BellIcon = icon(`
  <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
  <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
`)

export const LogoutIcon = icon(`
  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
  <polyline points="16 17 21 12 16 7"/>
  <line x1="21" y1="12" x2="9" y2="12"/>
`)

// ----- Módulos del sidebar -----
export const BoxIcon = icon(`
  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4
    a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
  <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
  <line x1="12" y1="22.08" x2="12" y2="12"/>
`)

export const ChartBarIcon = icon(`
  <line x1="18" y1="20" x2="18" y2="10"/>
  <line x1="12" y1="20" x2="12" y2="4"/>
  <line x1="6"  y1="20" x2="6"  y2="14"/>
  <line x1="2"  y1="20" x2="22" y2="20"/>
`)

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

export const UsersIcon = icon(`
  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
  <circle cx="9" cy="7" r="4"/>
  <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
  <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
`)

// ----- Acciones CRUD -----
export const PlusIcon = icon(`
  <line x1="12" y1="4" x2="12" y2="20"/>
  <line x1="4"  y1="12" x2="20" y2="12"/>
`)

export const EditIcon = icon(`
  <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
`)

export const TrashIcon = icon(`
  <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
`)

export const XIcon = icon(`
  <line x1="18" y1="6" x2="6" y2="18"/>
  <line x1="6"  y1="6" x2="18" y2="18"/>
`)

// ----- Búsqueda / filtros -----
export const SearchIcon = icon(`
  <circle cx="11" cy="11" r="8"/>
  <line x1="21" y1="21" x2="16.65" y2="16.65"/>
`)

export const FilterIcon = icon(`
  <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
`)

// ----- Contenido / entidades -----
export const ListIcon = icon(`
  <line x1="4" y1="6"  x2="20" y2="6"/>
  <line x1="4" y1="10" x2="20" y2="10"/>
  <line x1="4" y1="14" x2="20" y2="14"/>
  <line x1="4" y1="18" x2="20" y2="18"/>
`)

export const ClipboardIcon = icon(`
  <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
`)

export const ClockIcon = icon(`
  <circle cx="12" cy="12" r="9"/>
  <path d="M12 8v4l3 3"/>
`)

export const BuildingIcon = icon(`
  <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
`)

export const CursorArrowIcon = icon(`
  <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
`)
