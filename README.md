# Sistema de Gestión de Costos TDABC

## Descripción
Sistema Full Stack para la gestión de costos bajo la metodología de costos ABC en la variante **TDABC (Time-Driven Activity-Based Costing)**, generación de cotizaciones y registro de pedidos de venta, orientado a empresas de manufactura de productos personalizados. 

---

## Objetivo General
Desarrollar un sistema Full Stack para la gestión de costos ABC, generación de cotizaciones y registro de pedidos de venta en empresas de manufactura de productos personalizados, aplicando Clean Architecture y seguridad alineada a OWASP, con el fin de mejorar la precisión de los costos y el análisis de rentabilidad.

---

## Objetivos Específicos

1. Analizar el proceso actual de determinación de costos, cotizaciones y pedidos para identificar ineficiencias y definir requerimientos funcionales y no funcionales.
2. Aplicar Clean Architecture garantizando separación de responsabilidades, independencia de infraestructura y mantenibilidad.
3. Desarrollar el módulo de costeo ABC en su variante TADABC con cálculo de tasa de costo por capacidad por departamento y ecuaciones de tiempo por actividad.
4. Desarrollar el módulo de cotizaciones vinculado al módulo de costos, con conversión directa a pedidos de venta.
5. Desarrollar el módulo de pedidos de venta con trazabilidad desde la cotización aprobada.
6. Implementar mecanismos de seguridad siguiendo OWASP para proteger datos de costos, márgenes y comerciales.
7. Diseñar y ejecutar pruebas unitarias y de integración sobre los módulos desarrollados.

---

## Alcance

### Incluye
- Metodología de costos ABC en su variante TDABC
- Módulo de cotizaciones vinculado directamente al módulo de costos
- Registro de pedidos de venta con trazabilidad desde la cotización
- Seguridad alineada a OWASP (autenticación, autorización, validación de entradas, protección de datos sensibles)
- Pruebas unitarias y de integración sobre los tres módulos principales

### No incluye
- Módulo de inventario, control de stock ni control de tiempo en planta
- Comercio electrónico ni pasarelas de pago
- Publicación de datos reales de costos o márgenes (datos anonimizados por confidencialidad)
- Despliegue en entorno de producción real

---

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| Frontend | Vue 3 SPA |
| Backend | Laravel 12 (PHP 8.5) |
| Base de datos | PostgreSQL 18 |
---

## Arquitectura

```
cost_system_abc/
├── frontend/          # Vue 3 — SPA (Single Page Application)
│   └── src/
│       ├── modules/   # Módulos por dominio (costos, cotizaciones, pedidos)
│       ├── composables/
│       └── router/
│
├── backend/           # Laravel 12 — Clean Architecture
│   └── app/
│       ├── Domain/           # Entidades, interfaces de repositorio, value objects
│       ├── Application/      # Casos de uso (use cases), DTOs
│       ├── Infrastructure/   # Implementaciones: Eloquent, servicios externos
│       └── Presentation/     # Controllers HTTP, Form Requests, Resources
│
└── docker-compose.yml  # 3 servicios: app (PHP-FPM) | web (Nginx) | db (PostgreSQL)
```

**Flujo de una petición:**
`Vue 3 (Frontend) → Laravel12(Backend) → Use Case → Repository → PostgreSQL`

---

## Endpoints Core

### Autenticación
| Método | Endpoint | Descripción |
|---|---|---|
| POST | `/api/auth/login` | Iniciar sesión |
| POST | `/api/auth/logout` | Cerrar sesión |
| GET | `/api/auth/me` | Usuario autenticado |


#### Departamentos 
| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/departamentos` | Listar departamentos |
| POST | `/api/departamentos` | Crear departamento (costo total + capacidad práctica en minutos) |
| PUT | `/api/departamentos/{id}` | Actualizar departamento |

#### Recursos (que usan los departamentos)
| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/departamentos/{id}/recursos` | Listar recursos del departamento |
| POST | `/api/departamentos/{id}/recursos` | Agregar recurso (nombre, tipo, costo_mensual) |
| PUT | `/api/departamentos/{id}/recursos/{recursoId}` | Actualizar recurso |
| DELETE | `/api/departamentos/{id}/recursos/{recursoId}` | Eliminar recurso |

#### Actividades y ecuaciones de tiempo
| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/actividades` | Listar actividades |
| POST | `/api/actividades` | Crear actividad (departamento + tiempo base en minutos) |
| PUT | `/api/actividades/{id}` | Actualizar actividad |
| GET | `/api/inductores_tiempo` | Listar inductores de tiempo |
| POST | `/api/inductores_tiempo` | Crear inductor de tiempo |
| POST | `/api/actividades/{id}/inductores_tiempo` | Asociar inductor a actividad  |
| DELETE | `/api/actividades/{id}/inductores_tiempo/{id}` | Desasociar inductor |

#### Cálculo de costos por producto
| Método | Endpoint | Descripción |
|---|---|---|
| POST | `/api/productos/{id}/actividades` | Asociar actividades a producto con cantidades de inductores |
| GET | `/api/productos/{id}/listado_costos` | Desglose: tiempo consumido × tasa por actividad/departamento |

### Cotizaciones
| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/cotizacion` | Listar cotizaciones |
| POST | `/api/cotizacion` | Crear cotización |
| GET | `/api/cotizacion/{id}` | Detalle de cotización |
| PATCH | `/api/cotizacion/{id}/estado` | Cambiar estado |

### Pedidos de Venta
| Método | Endpoint | Descripción |
|---|---|---|
| GET | `/api/ordenes` | Listar pedidos |
| GET | `/api/ordenes/{id}` | Detalle de pedido |
| PATCH | `/api/ordenes/{id}/estado` | Actualizar estado del pedido |

---

## Cómo Ejecutar el Proyecto

### Requisitos
- Docker y Docker Compose instalados

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd cost_system_abc

# 2. Copiar variables de entorno
cp .env.example .env
# Editar .env con los valores reales

# 3. Levantar los contenedores
docker compose up --build -d

# 4. Ver logs (opcional)
docker compose logs -f app
```

El backend queda disponible en `http://localhost:8000`

> En el primer arranque el entrypoint instala Laravel, configura el `.env`, genera el `APP_KEY` y ejecuta las migraciones automáticamente.

---

## Variables de Entorno

Archivo: `.env` en la raíz del proyecto

```env
# Puerto de acceso al backend
APP_PORT_BACKEND=8000

# Puerto expuesto de PostgreSQL (para clientes externos como pgAdmin)
DB_PORT=5432

# Base de datos
DB_DATABASE=cost_system
DB_USERNAME=laravel
DB_PASSWORD=secret
```

> Las variables `DB_HOST` y la configuración interna de Laravel son inyectadas automáticamente por Docker Compose al contenedor `app`.
