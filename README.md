# Adsycraft (Laravel)

Production-grade SaaS for **Meta Pages + Instagram Business** automation: content creation, scheduling, inbox, ads, AI features, and research—with a multi-tenant workspace model. Built with Laravel.

**Repository:** [https://github.com/Artixcore/adsycraft-laravel](https://github.com/Artixcore/adsycraft-laravel)

## Tech stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL, Redis (queue, cache, session)
- **Frontend:** Blade, vanilla JS, Vite, Tailwind CSS
- **Auth:** Laravel Sanctum (API tokens + session); role-based access (Admin, User, Viewer); last login tracking

## Features

### Authentication & user management

- User registration (email + password, bcrypt) and login (session-based)
- Role-based access: `ADMIN`, `USER`, `VIEWER` with route-level guards (`role:admin,user` middleware)
- Last login tracking (`last_login_at`)

### Workspaces (multi-tenancy)

- Workspace CRUD (name, slug); subscription tiers (`free`, `pro`, `enterprise`) and status
- Workspace–user membership; workspace-scoped business accounts
- Business accounts can be filtered by `workspace_id`

### Meta OAuth & page connection

- Meta OAuth flow; long-lived user tokens (encrypted)
- Connect Facebook Pages and Instagram Business; page/IG tokens stored encrypted
- Token refresh job (daily 02:00); page list, select assets, disconnect

### Content (posts)

- Post drafts: create, update, delete; caption, media type (text/image/video/carousel), media URL, content pillar
- Post status: draft, scheduled, publishing, published, failed, cancelled
- Schedule draft to a page at a specific time; publish now; list/calendar with filters (status, meta_asset_id)
- Publish history via PostLog; stub publish (no real Meta API yet)

### AI

- Multiple providers: OpenAI, Gemini, Grok; encrypted API keys; primary + fallback
- AI connections CRUD per business; make-primary, test endpoint
- AI request logging (provider, model, request type, tokens, cost, status, latency)
- Stub caption generator for autopilot

### Brand voice

- Brand voice CRUD per workspace (optional per-page via `meta_asset_id`)
- Tone, style, keywords, avoid-words, compliance rules (JSON), language

### Webhooks

- `GET` / `POST` `/webhooks/meta` (verification token + handle)
- WebhookEvent storage (source, event_type, payload, processing_status)
- Message and comment processors (stub); event type detection from payload

### Audit & user metadata

- Audit logs: action, resource type/ID, user, workspace, IP, user-agent; list/filter API
- User metadata: encrypted key/value storage (reference types: env_file, config_file, custom), tags, description; CRUD API

### Security & API

- API rate limiting (60/min); request ID middleware (X-Request-ID)
- CORS configurable via `CORS_ALLOWED_ORIGINS`
- Swagger UI at `/api-docs`; OpenAPI spec at `/openapi.json`
- Content-Range header on list endpoints (workspaces, businesses, posts, audit-logs)
- Health check: `GET /up`

### Page insights & research

- Page insights: store metrics (e.g. page_impressions, page_reach) by period (day); scheduled fetch job (daily 02:00)
- Research: trigger page research (stub); product research model (name, description, price hints, pain points, sources, confidence); get results API

### Inbox

- Conversations list (filter by meta_asset_id, archived); messages list (paginated)
- Reply to conversation (queued via ReplyMessageJob)
- Conversation and InboxMessage models (stub; no real Meta Messaging API yet)

### Ads

- Ad accounts (workspace-scoped); campaigns, ad sets, ads (models + migrations)
- AdsOptimizerJob (stub) scheduled daily
- API: list ad accounts, list campaigns per ad account

### Background jobs

- **PublishDuePostsJob** — every 10 min (stub publish)
- **RefreshMetaTokensJob** — daily 02:00 (stub refresh)
- **FetchPageInsightsJob** — daily 02:00 (stub fetch)
- **AdsOptimizerJob** — daily
- **GenerateDailyContentJob** — hourly (autopilot)
- **ReplyMessageJob** — on-demand (inbox reply)

## Routes

### Web

- `/` (welcome)
- `/api-docs` (Swagger UI)
- `/openapi.json` (OpenAPI spec)
- `/dashboard`, `/dashboard/connectors` (auth)
- `/connectors/meta/callback` (Meta OAuth callback)

### API (auth:sanctum)

- **User:** `GET /api/user`
- **Workspaces:** `GET|POST /api/workspaces`, `GET|PUT|DELETE /api/workspaces/{workspace}`
- **Brand voices:** `GET|POST /api/workspaces/{workspace}/brand-voices`, `GET|PUT|DELETE /api/workspaces/{workspace}/brand-voices/{brand_voice}`
- **Audit logs:** `GET /api/audit-logs` (query: workspace_id, user_id, action, from, to)
- **User metadata:** `GET|POST /api/user-metadata`, `GET|PUT|DELETE /api/user-metadata/{metadata}`
- **Businesses:** `GET|POST /api/businesses`, `GET|PUT|DELETE /api/businesses/{business}` (query: workspace_id); `POST .../toggle-autopilot`, `POST .../generate-today`
- **Posts:** `GET|POST /api/businesses/{business}/posts`, `GET|PUT|DELETE .../posts/{post}`, `POST .../posts/{post}/schedule`, `POST .../posts/{post}/publish`, `GET .../calendar` (query: status, meta_asset_id)
- **AI connections:** `GET|POST /api/businesses/{business}/ai-connections`, `PUT|DELETE .../ai-connections/{connection}`, `POST .../make-primary`, `POST .../test`
- **Meta connector:** `GET .../connectors/meta/status`, `POST .../auth-url`, `GET .../assets`, `POST .../assets/select`, `POST .../disconnect`
- **Insights:** `GET /api/businesses/{business}/insights` (query: meta_asset_id, period, from, to)
- **Research:** `POST /api/businesses/{business}/research/trigger`, `GET .../research/results` (query: meta_asset_id)
- **Inbox:** `GET /api/businesses/{business}/inbox/conversations`, `GET .../inbox/conversations/{conversation}/messages`, `POST .../inbox/conversations/{conversation}/reply`
- **Ads:** `GET /api/ads/ad-accounts`, `GET /api/ads/ad-accounts/{ad_account}/campaigns`

### Webhooks

- `GET|POST /webhooks/meta` (verify + handle; stores WebhookEvent, dispatches message/comment processors)

## Requirements

- PHP 8.2+
- Composer
- Node.js / npm
- MySQL
- Redis

## Installation

1. Clone the repo and enter the project directory.
2. Copy env and generate key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Configure `.env`: `DB_*`, `REDIS_*`, `QUEUE_CONNECTION=redis`. Optional: `META_WEBHOOK_VERIFY_TOKEN`, `CORS_ALLOWED_ORIGINS`.
4. Install dependencies and migrate:
   ```bash
   composer install
   php artisan migrate
   ```
5. Build frontend:
   ```bash
   npm install && npm run dev
   ```
   Or for production: `npm run build`.
6. Start the app:
   ```bash
   php artisan serve
   ```
7. **Queue worker** (separate terminal): `php artisan queue:work` or `php artisan queue:work redis --tries=3`.
8. **Scheduler** (separate terminal): `php artisan schedule:work` (PublishDuePostsJob every 10 min, RefreshMetaTokensJob + FetchPageInsightsJob + AdsOptimizerJob daily 02:00, content:generate-daily hourly).

## Verification

Log in, open `/dashboard` and `/dashboard/connectors`. Create a workspace (or use backfilled one), create a business under it, add an AI connection and set it as primary, then trigger “Generate today”. Run the queue worker and confirm posts are created. Use `/api-docs` to explore the API.

## License

MIT License. See [LICENSE](https://opensource.org/licenses/MIT) for details.

---

*Powered by [Laravel](https://laravel.com).*
