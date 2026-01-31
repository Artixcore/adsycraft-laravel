# MetaGrowth Autopilot

SaaS for autopilot content: manage businesses, scheduled posts, AI connectors (BYOK), and Meta webhooks. Built with Laravel.

**Repository:** [https://github.com/Artixcore/adsycraft-laravel](https://github.com/Artixcore/adsycraft-laravel)

## Tech stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL, Redis (queue, cache, session)
- **Frontend:** Blade, vanilla JS, Vite, Tailwind CSS
- **Auth:** Laravel Sanctum (API tokens + session for dashboard)

## Features

- **Businesses:** CRUD, timezone, niche, tone, language, posts per day, autopilot on/off
- **Posts:** Scheduled posts, calendar feed, status (draft / scheduled / published / failed), stub publishing (no real Meta API yet)
- **AI connectors (BYOK):** OpenAI, Gemini, Grok; encrypted API keys; primary/fallback; test endpoint; stub caption generator when primary AI is set
- **Meta connector:** OAuth scaffold (status, connect/disconnect placeholder URL; no real Meta API yet)
- **Webhooks:** `GET` / `POST` `/webhooks/meta` (verification + log payload; no signature verification yet)
- **Jobs:** GenerateDailyContentJob (idempotent per business per day), PublishDuePostsJob (every 10 min), scheduler

## Routes

- **Web:** `/` (welcome), `/dashboard` (auth), `/dashboard/connectors` (auth)
- **API (auth:sanctum):** `/api/businesses` (CRUD), `/api/businesses/{id}/posts`, `/api/businesses/{id}/calendar`, `/api/businesses/{id}/generate-today`, `/api/businesses/{id}/toggle-autopilot`, `/api/businesses/{id}/ai-connections` (CRUD + make-primary + test), `/api/businesses/{id}/connectors/meta/status`, `/api/businesses/{id}/connectors/meta/connect`, `/api/businesses/{id}/connectors/meta/disconnect`
- **Webhooks:** `/webhooks/meta` (GET verify, POST handle)

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
3. Configure `.env`: `DB_*`, `REDIS_*`, `QUEUE_CONNECTION=redis`. Optional: `META_WEBHOOK_VERIFY_TOKEN` for webhook verification.
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
8. **Scheduler** (separate terminal): `php artisan schedule:work` (e.g. PublishDuePostsJob every 10 minutes, content:generate-daily hourly).

## Verification

Log in, open `/dashboard` and `/dashboard/connectors`. Create a business, add an AI connection and set it as primary, then trigger “Generate today”. Run the queue worker and confirm posts are created with varied stub captions when a primary AI connection exists.

## License

MIT License. See [LICENSE](https://opensource.org/licenses/MIT) for details.

---

*Powered by [Laravel](https://laravel.com).*
