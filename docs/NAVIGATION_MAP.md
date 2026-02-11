# Navigation Map

Where every feature lives in the Meta Automation Application (UI).

## User / Home (authenticated)

| Feature | Route | Layout |
|---------|--------|--------|
| Home (dashboard) | `GET /dashboard` | app |
| Your automations, create business, selected business (posts, calendar) | Same page | app |
| Connectors (Meta, AI providers) | `GET /dashboard/connectors` | app |
| Admin (link visible only for admin role) | `GET /admin` | — |

## Admin (role: admin)

| Feature | Route | Layout |
|---------|--------|--------|
| Admin dashboard (overview cards, quick actions) | `GET /admin` | admin |
| Users management | `GET /admin/users` | admin |
| Roles/permissions (UI display) | `GET /admin/roles` | admin |
| Meta accounts (connected pages/status) | `GET /admin/meta-accounts` | admin |
| Automations list (search, filter, status badges) | `GET /admin/automations` | admin |
| Create automation (redirect to user dashboard) | `GET /admin/automations/create` | admin |
| Automation detail (read-only) | `GET /admin/automations/{id}` | admin |
| Logs / history (filterable) | `GET /admin/logs` | admin |
| Settings (sections) | `GET /admin/settings` | admin |

## Guest / Public

| Feature | Route | Layout |
|---------|--------|--------|
| Welcome | `GET /` | (standalone) |
| API docs | `GET /api-docs` | (standalone) |
| Login / Register / Forgot password | auth routes | guest |

## Layouts

- **app** – User app: header (logo, Home, Connectors, Admin if admin, user menu), main content, toast container.
- **admin** – Admin: left sidebar (Dashboard, Users, Roles, Meta accounts, Automations, Logs, Settings), top bar (quick actions, user menu), main content, toast container.
- **guest** – Auth pages (login, register, etc.).
- **dashboard** – Legacy layout (still present); dashboard/connectors now use **app**.
