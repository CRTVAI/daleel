# AGENTS.md

## Overview
- This is a Laravel 12 / PHP 8.2 application with a Blade-first UI, Sanctum-protected APIs, and a browser-extension integration layer.
- The product combines three main areas:
  1. public marketing and blog pages,
  2. authenticated learning flows (dashboard, courses, watch pages, roadmaps, bookmarks, onboarding),
  3. extension/team telemetry APIs used for activity sync, suggestions, analytics, and employee monitoring.
- Most frontend behavior lives directly in Blade templates with inline CSS/JS plus Bootstrap CDN assets. There is only a very small Vite/Alpine bundle in `resources/js/app.js`.

## Essential commands
### Setup / install
- `composer setup`
  - Installs PHP dependencies, copies `.env` if missing, generates the app key, runs migrations, installs npm packages, and builds assets.
- Manual setup sequence from `README.md`:
  - `composer install`
  - `npm install && npm run build`
  - copy `.env.example` to `.env`
  - `php artisan key:generate`
  - `php artisan migrate --seed`

### Local development
- `composer dev`
  - Starts the Laravel server, queue listener, pail log viewer, and Vite together via `concurrently`.
- `php artisan serve`
- `npm run dev`

### Testing / verification
- `composer test`
  - Clears config, then runs `php artisan test`.
- `php artisan test`
- Tests run against in-memory SQLite (`phpunit.xml` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:`).

### Formatting / maintenance
- `vendor/bin/pint`
  - `laravel/pint` is installed, but there is no Composer script wrapping it.
- `php artisan optimize:clear`
  - Also exposed through the public `/clear-cache` route.

## Repository structure
### Backend
- `app/Http/Controllers/`
  - Main application controllers, including admin CRUD, dashboards, learning flows, roadmaps, AI mentor, team analytics, and subtitle conversion.
- `app/Http/Controllers/Api/`
  - API endpoints for auth, blog ingestion, external content ingestion, browsing history, and extension flows.
- `app/Http/Controllers/Api/Extension/`
  - Extension link/unlink, activity sync, contextual recommendations, verification codes, and suggestion endpoints.
- `app/Models/`
  - Core entities: `Content`, `Course`, `Tool`, `User`, `UserRoadmap`, browser/extension telemetry models, blog posts, and team-management models.
- `app/Services/ToolDetector.php`
  - Central URL/domain-to-tool classification utility.
- `app/Http/Middleware/`
  - `AdminMiddleware` for admin gating and a global permissive `CorsMiddleware`.

### Frontend / views
- `resources/views/layouts/`
  - Multiple parallel layout systems:
    - `guest.blade.php` uses Vite assets,
    - `public.blade.php`, `admin.blade.php`, `user.blade.php`, `mentor.blade.php`, and `app.blade.php` rely heavily on Bootstrap CDN plus inline styles/scripts.
- `resources/views/admin/`, `learn/`, `roadmap/`, `team/`, `blog/`, `auth/`
  - Feature-specific Blade templates.
- `public/site.css` and `public/site.js`
  - Public marketing pages use these static assets instead of Vite.
- `resources/js/app.js`
  - Minimal Alpine bootstrap only.

### Routes and bootstrap
- `bootstrap/app.php`
  - Registers web routes, API routes, console routes, health check `/up`, global CORS middleware, and the `admin` middleware alias.
- `routes/web.php`
  - Mix of closures and controller actions; includes public pages, admin area, authenticated learning area, roadmap flows, team dashboards, and blog routes.
- `routes/api.php`
  - Public auth/ingestion routes plus Sanctum-protected extension and history endpoints.

## Architecture and control flow
### Web app
- Root and most marketing pages are rendered directly from Blade views.
- Authenticated users hit `/dashboard`, which redirects admins to `admin.dashboard` and everyone else to `user.dashboard` after email-verification checks.
- Learning content centers on the `Content` model and related `UserVideoProgress` records.
- Roadmaps are stored in `user_roadmaps.curriculum` and rendered from `RoadmapController` / `LearningController`.

### Extension flow
1. The extension calls `POST /api/extension/link` with a verification code and `device_id`.
2. `LinkController` resolves either:
   - an employee permanent connection code on `users.connection_code`, or
   - a one-time hashed code in `extension_verification_codes`.
3. Linking creates or updates an `ExtensionDevice`, then issues a Sanctum token.
4. Subsequent extension requests go through `auth:sanctum` routes in `routes/api.php`.
5. `ActivityController` stores sessions, metrics snapshots, daily rollups, recommendation events, and help requests, usually keyed to the current device.
6. Suggestions can also be fetched anonymously through `GET /api/extension/suggest`, which attempts to resolve the user from `X-Extension-Device-Id` or `?device_id=`.

### Recommendation / search stack
- Personalized recommendations are built from a mix of:
  - explicit user interests,
  - browsing history domains,
  - extension session domains,
  - `connected_tools` metadata on `Content`.
- `AdvancedSearchController` uses a two-step approach:
  1. GPT parses the query into type + tool hints.
  2. The app branches into video/course/roadmap handlers.
- `AiController` also does heuristic candidate filtering first, then optionally asks OpenAI to rank candidates when `OPENAI_API_KEY` is set.

### Team analytics
- Team views aggregate employee extension data rather than normal learner progress.
- `TeamController` uses deduplicated active-time calculations to avoid overcounting when multiple browser sessions overlap.
- Employee accounts are real `users` rows with `is_employee=true`, `parent_id` pointing at the manager/company user, and generated placeholder emails.

## Conventions and patterns
### Data modeling
- `Content` is the center of most product flows.
  - `connected_tools` is cast to an array in `app/Models/Content.php`.
  - Duration and thumbnail display are normalized through accessors (`resolved_duration_seconds`, `duration_label`, `thumbnail_url`).
- `User` also stores product flags and profile metadata directly on the model (`is_admin`, `account_type`, `interests`, `connections`, team fields, pro-trial fields).
- `Tool` is lightweight and mainly acts as metadata for connected platforms shown across the UI.

### Query style
- Controllers frequently compose Eloquent queries inline instead of pushing logic into dedicated services.
- Existing code mixes two different ways of querying tool associations on `Content.connected_tools`:
  - JSON-aware queries such as `whereJsonContains(...)`, and
  - string `LIKE` checks against the serialized column.
- When changing recommendation/search logic, check nearby code first and keep behavior consistent with that flow instead of assuming one canonical query style already exists.

### UI style
- This codebase is not a single coherent frontend stack.
- Public, admin, mentor, and user pages commonly use Bootstrap CDN and inline CSS/JS directly in Blade files.
- Auth guest pages are the main place using Vite/Tailwind-style assets.
- Follow the surrounding template instead of trying to standardize unrelated pages during a small change.

### Validation / security patterns
- Controllers generally validate directly inside action methods with `$request->validate(...)`.
- Admin access is enforced with middleware alias `admin`.
- Extension APIs depend on Sanctum tokens plus device resolution.

## Important gotchas
- `routes/web.php` defines `/roadmap` twice:
  - once in the authenticated user block pointing to `LearningController`,
  - later in the roadmap wizard block pointing to `RoadmapController`.
  The later declaration is the one to watch when changing roadmap routing.
- `routes/web.php` ends with a catch-all `/{page}` route. Add new specific routes before it, or they may never match. If you add a new top-level slug that should bypass the catch-all restrictions, update its regex exclusion list too.
- `RoadmapController::show()` can mutate saved roadmap curriculum via `healRoadmapCurriculum()` if referenced content records disappeared. Rendering a roadmap is therefore not purely read-only.
- Course/watch/roadmap progress treats `>= 90%` as completed and prevents progress regression by keeping the max watched/duration values.
- Team and dashboard analytics intentionally deduplicate overlapping extension sessions by minute buckets; do not replace those calculations with naive sums unless inflation is acceptable.
- Employee extension codes are rotated after a successful link in `LinkController`; do not assume an employee code remains reusable.
- Free Plan users are limited to one active extension device in `LinkController`.
- `ActivityController` prefers `X-Extension-Device-Id` to resolve the active device; there is a legacy fallback to the most recently active device when the header is absent. Preserve header-aware behavior when touching extension ingestion.
- Global CORS is currently permissive (`*`) through `CorsMiddleware` for both preflight and normal responses.
- `SubtitlesController` fetches subtitle files from the provided URL and converts SRT to WebVTT on the fly; changes there affect cross-origin subtitle playback.
- `AppServiceProvider` injects onboarding-modal data globally from `Content`, `learning_goals`, `experience_levels`, and active `Tool` rows. Changes to those tables can affect modal rendering outside the page you are editing.
- `guest.blade.php` references `@vite(['resources/css/app.css', 'resources/js/app.js'])`, while `vite.config.js` lists `resources/sass/app.scss` and `resources/js/app.js` as inputs. Verify the intended asset entrypoint before changing auth-page styling.

## Testing reality
- Automated coverage is currently thin and mostly Breeze-style auth/profile tests in `tests/Feature/Auth/*` plus `tests/Feature/ProfileTest.php`.
- There are no observed tests covering the extension APIs, roadmap generation/healing, team analytics, AI mentor flows, or recommendation logic.
- For risky changes in those areas, manual verification is important even if `php artisan test` stays green.
- Existing feature tests commonly use `RefreshDatabase` and factory-created users.

## External services and environment-sensitive features
- OpenAI-backed behavior appears in at least:
  - `AdvancedSearchController`,
  - `AiController`,
  - extension recommendation generation in `Api/Extension/ActivityController`.
- These features use `config('services.openai.key')` or `env('OPENAI_API_KEY')`. Without a key, some flows fall back to heuristics instead of failing hard.
- Social login configuration exists in `config/services.php` for Google.
- AWS/S3-related configuration exists in `config/services.php`, `config/filesystems.php`, and `config/cache.php`.
- Queue configuration defaults to the database driver, and `composer dev` starts `php artisan queue:listen`.

## Existing docs worth checking
- `README.md` for basic product/setup notes.
- `BLOG_API.md` for blog ingestion/listing API examples.
- `suggestion-api.md` for extension suggestion endpoint behavior and response shapes.

## Practical guidance for future agents
- Start by checking whether you are in a public-marketing page, an auth Blade flow, an admin CRUD flow, or an extension/API flow; each area uses different conventions.
- When changing top-level routes, inspect both the catch-all page route and duplicate route declarations first.
- When touching recommendations, roadmaps, or dashboards, inspect both `BrowsingHistory` and extension-session usage before concluding where personalization comes from.
- When touching extension APIs, trace both `routes/api.php` and the `ExtensionDevice` / Sanctum token lifecycle together.
- When touching roadmap rendering, account for curriculum self-healing and the non-regression completion logic.
- Do not assume frontend assets are centralized; many pages ship their own CSS/JS inline or via CDN.
