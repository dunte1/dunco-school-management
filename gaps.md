# COMPLETE CODEBASE GAP ANALYSIS
## dunco-school-management

**Audit date:** 2026-09-14
**Auditor:** Principal Engineer / Architect / Security / QA review (automated + manual)
**Audit method:** static inspection of the full repository (1046 PHP files, 48 Vue files, 44 Blade views, 193 migrations), runtime route enumeration (`php artisan route:list` = 853 routes), and cross-referencing of routes → controllers → methods → views → models → migrations.

---

### Baseline note (read first)

The repository was committed in a **non-bootable** state. During a prior setup session the following were already repaired purely to reach a running baseline, and this audit reflects that repaired baseline:

- `composer.json` scribe constraint `^4.38` → `^5.0` (Laravel 12 incompatibility); `composer.lock` regenerated.
- `config/sentry.php` (empty file → `return [];`).
- `config/backup.php` (stale for spatie/laravel-backup v10 → v10 structure).
- 16 module route files that had been committed as a single line (broken `<?php` + line-comments swallowing the file) reconstructed from `dist/` copies.
- `Modules/ChatBot/Http/Controllers/ChatBotController.php` duplicated method block removed.
- `routes/api.php` route to non-existent `SessionTemplateController` removed.
- `resources/views/welcome.blade.php` `@vite` entry corrected.

These fixes make the app **boot and serve `/` + `/login` (200)** but do **not** address the systemic gaps below.

**Legend for completion levels:** LEVEL 0 = not found · 1 = UI only · 2 = backend only · 3 = incomplete · 4 = functional end-to-end · 5 = functional + validated + authorized + error-handled + tested.

---

## 1. Executive Summary

### Overall project status

This is an **ambitious, partially-assembled Laravel 12 + Inertia/Vue + modular monolith** for school management (17 nwidart modules). The backend contains a large volume of code — 176 migrations (169 unique tables), 116 model files, ~120 controllers, 106 Finance routes, 105 Academic routes — but a very large fraction of it is **not wired end-to-end**. Concretely: module service providers are disabled, module API routes are never loaded, Sanctum/Spatie/Tenancy/Broadcast providers are not booted, 78 route actions call methods that do not exist, 159 controller `view()` calls point at Blade files that do not exist, 15 model files are zero-byte, the Inertia root template is not configured, the Android client cannot compile, CI is broken, and several critical security holes exist (unauthenticated Finance CRUD, any user can rewrite `.env`, hardcoded live AI API key).

The system is best described as: **a working authentication shell + a handful of working module UIs (Hostel, Communication, ChatBot, Student/Academic, parts of HR/Transport) surrounded by a large volume of half-wired module scaffolding.**

### Estimated completion percentage

**Evidence-based weighted estimate: ~40%** for "runs and demos core screens", and **~12–15%** for "production-ready, secure, tested".

How this was estimated (weights reflect business criticality):

| Area | Weight | Est. complete | Basis |
|---|---|---|---|
| Web authentication (login/register/reset/logout) | 10 | 90% | Tests pass, controllers+views exist |
| Authorization / RBAC enforcement | 10 | 20% | Spatie not booted; aliases undefined; UI-only gating |
| Student / Academic | 10 | 70% | Real controller + views; no authz |
| Finance | 12 | 35% | CRUD partial; gateway/reports/recon missing |
| Examination | 10 | 30% | Controllers hardcode demo data; views missing |
| Attendance | 8 | 45% | Real engine in Academic; module controller stubbed |
| Timetable | 8 | 40% | Generator real; approval workflow missing |
| Library | 6 | 35% | Books real; borrow/reports hardcoded |
| HR | 8 | 55% | Many resource methods missing |
| Hostel | 6 | 80% | Most complete module |
| Transport | 5 | 50% | Wrong model namespace; missing views |
| Communication | 7 | 75% | Email/SMS/push real; 2 routes broken |
| ChatBot / AI | 6 | 70% | Real Gemini/OpenAI; key hardcoded |
| Portal (student/parent/teacher) | 6 | 20% | Demo data, empty models, wrong imports |
| Settings | 5 | 40% | Missing views; backup stubs |
| Dashboards | 5 | 50% | Missing models masked to zero |
| Notifications | 5 | 30% | No-op controller; missing views |
| API (Sanctum + module APIs) | 12 | 10% | Module APIs not loaded; sanctum undefined |
| Frontend (Inertia/Vue) | 10 | 15% | No `@inertia`; orphaned pages |
| Admin/Blade UI | 10 | 55% | Broad but many dead links/disabled controls |
| Database integrity | 10 | 45% | Duplicates, FK ordering, case paths |
| Testing | 6 | 15% | Only auth/profile tests |
| Security | 10 | 30% | Multiple criticals |
| Deployment / CI | 6 | 20% | CI broken; no Docker despite docs |
| Mobile (Android) | 5 | 10% | Non-compilable |
| **Total** | **196** | **≈41% / ~13% prod-ready** | weighted sum 81.25 / 196 |

### Critical findings (summary)

1. Framework providers disabled → `auth:sanctum`, `role:`, `permission:`, `admin` middleware aliases are **undefined**; all API auth is non-functional.
2. Entire **Finance module is unauthenticated** (no middleware on the route group).
3. All **module API routes are never loaded** (0 `api/v1/*`, 0 `mobile/v1/*` at runtime); mobile API controllers mostly missing.
4. **Hardcoded live-looking Gemini API key** + TLS verification disabled.
5. Any authenticated user can **rewrite `.env`** through ChatBot admin routes.
6. **Unauthenticated DB-seeding / debug routes** (`/seed-transport`, `/test-transport`, `/test`).
7. Inertia root template lacks `@inertia`/`@vite` → the Vue frontend cannot mount.
8. **78 route actions → missing controller methods**; **159 `view()` refs → missing Blade files**.
9. Email verification is a no-op (`MustVerifyEmail` not implemented) and registration auto-verifies.
10. Duplicate/conflicting migrations + FK-ordering hazards; several module migration folders are not loaded (case-sensitive path).

### High-priority findings (summary)

- Authorization is overwhelmingly UI-only; unscoped `findOrFail($id)` across modules (IDOR risk).
- HR/Timetable/Finance/Core/Portal resource methods missing (show/edit/update/destroy/approval).
- Zip Slip on student bulk-import.
- Global secrets (SMTP/SMS/payment tokens) stored in plaintext and editable by any user.
- Communication module provider unregistered; Notification provider is a no-op.
- Scheduler not wired (backups, fee reminders never run); no jobs.
- Broken CI (Laravel DB name; Android Java 21 vs JDK 17); no deployment artifacts.
- Android app cannot compile.
- Sidebar links to non-existent URIs/routes.
- Duplicate/overlapping modules causing table/schema conflicts (`rooms`, `room_allocations`, RBAC pivots).

### Medium findings (summary)

- Missing request validation + `$request->all()` mass assignment in Finance/HR; no API Resources; inconsistent error envelopes; no DB transactions.
- Placeholder dashboards ("under development"), "coming soon" alert buttons, disabled export buttons, dead `href="#"`.
- `.env.example` omits AI/SMS/backup/performance variables; Sentry is a stub.
- CORS `*`, weak CSP (`unsafe-inline`), session cookie hardening off, Android logs full HTTP bodies.
- No shared loading/empty/error UI; hand-rolled pagination; accessibility gaps.
- Only 25 tests; no module/business tests.

### Low findings (summary)

- `console.log` in components, unused imports, commented-out provider/method registrations, stale root scripts, orphan views/pages, documentation materially diverging from reality, wrong model namespaces (`Transport`), `seed_database.php` 0 bytes.

---

## 2. Technology Stack

| Layer | Technology | Evidence |
|---|---|---|
| Backend | Laravel 12.69.2, PHP 8.2+ (running 8.4.8) | `composer.json:9,13` |
| Frontend | Inertia.js 2, Vue 3.4, Vite 6, Tailwind 3/4, TypeScript 5.6 | `package.json`, `vite.config.js` |
| Module system | nwidart/laravel-modules (installed, **disabled at runtime**) | `composer.json:17`, `bootstrap/app.php:55-61` |
| Database | SQLite by default in `.env.example`; MySQL supported (CI) | `config/database.php`, `.github/workflows/ci.yml` |
| Auth | Custom Breeze-like scaffold; Sanctum installed but **not booted** | `routes/auth.php`, `config/auth.php`, `bootstrap/app.php:32-62` |
| Authorization | Custom `App\Models\Role/Permission` + `HasPermissions` trait; spatie/laravel-permission installed but **not booted** | `app/Traits/HasPermissions.php`, `config/permission.php` |
| Tenancy | stancl/tenancy installed but **not booted** | `composer.json:23`, `routes/tenant.php` |
| Payments | PayPal/M-Pesa settings/tables only; **no gateway calls** | Finance migrations, `PaymentController.php` |
| AI | Gemini + OpenAI via `Http::`, with canned fallbacks | `Modules/ChatBot/Services/*` |
| SMS/Email/Push | Africa's Talking cURL, Laravel Mail, FCM cURL | `Communication/Services/SmsService.php`, `FcmService.php` |
| Queue | `database` driver; **no jobs defined** | `config/queue.php`, no `app/Jobs` |
| Scheduler | legacy `app/Console/Kernel.php` only; **not wired in Laravel 12** | `bootstrap/app.php` (no `withSchedule`) |
| Cache | `database` | `config/cache.php:18` |
| Storage | `local` private + S3 config | `config/filesystems.php` |
| Monitoring | sentry/sentry-laravel installed but config is stub `return []` | `config/sentry.php` |
| Backup | spatie/laravel-backup v10 installed | `config/backup.php` |
| Testing | Pest 3 + PHPUnit 11; sqlite `:memory:` | `phpunit.xml`, `tests/Pest.php` |
| Build | Composer, npm/Vite, Gradle (Android) | `composer.json`, `package.json`, `android-app/*.gradle` |
| Mobile | Kotlin + Compose + Hilt + Room + Retrofit (non-compilable) | `android-app/` |
| CI | GitHub Actions (Laravel + Android) — both likely fail | `.github/workflows/` |

---

## 3. System Architecture

### Actual (as built)

```
Browser / Mobile
   │
   ├─ Web (session auth, Blade + partial Inertia)
   │     routes/web.php ──┐
   │     routes/auth.php  │  (bootstrap/app.php withRouting web:)
   │     routes/modules.php ── requires each Modules/*/routes/web.php ONLY
   │            │
   │            ├─ Controllers (app/ + Modules/*/Http/Controllers)
   │            │     └─ many methods MISSING; many view() targets MISSING
   │            ├─ Models (app/Models + Modules/*/Models|Entities)
   │            │     └─ 15 zero-byte models; some namespaces wrong
   │            └─ Blade views (partial) + orphaned Vue pages
   │
   └─ API (Sanctum guard UNDEFINED → non-functional)
         routes/api.php (sidebar + attendance, unreachable auth)
         Modules/*/routes/api.php  ── NEVER LOADED
         Modules/API/routes/mobile.php ── NEVER LOADED + missing controllers
```

### Intended (per module.json / docs) but not active

```
nwidart module providers → RouteServiceProvider (web+api), views, migrations,
events, commands  ── ALL DISABLED (bootstrap/app.php:55-61), module.json mostly lists providers
```

### Key architectural facts (verified)

- `bootstrap/app.php:32-62` registers a **hand-picked provider list** and explicitly comments out the module package and module providers.
- `bootstrap/app.php:13-28` registers **no middleware aliases** → `admin`, `role:`, `permission:` are undefined → routes using them 500.
- `config/auth.php:38-43` defines **only the `web` guard** → `auth:sanctum` fails.
- `routes/modules.php:12` loads **`routes/web.php` only** → every module `api.php` (and `mobile.php`) is dead.
- `app/Providers/AppServiceProvider.php:36,39` — `registerModuleProviders()` disabled; instead `boot():92-111` manually registers **lowercase view namespaces** and loads migrations from `Modules/*/Database/Migrations` (capital) — works only on case-insensitive filesystems.
- Runtime confirmation: 853 route lines; `api/v1` = 0, `mobile/v1` = 0.

### Disconnected components

- Mobile API (`Modules/API/routes/mobile.php`) ↔ Android app: neither controller exists nor routes load.
- Inertia pages ↔ controllers: `resources/views/app.blade.php` has no Inertia directives.
- Module providers ↔ routes/migrations/views: providers disabled; only ad-hoc loading works.
- Scheduler ↔ commands: commands exist, schedule never runs.
- Policies ↔ controllers: one policy exists, never invoked.

---

## 4. Feature Inventory

> Status key: COMPLETE / PARTIAL / MISSING / BROKEN / MOCKED / STUB / DEAD / DUPLICATED / UNVERIFIED.
> "Level" = completion level 0–5 (defined above). Evidence is representative, not exhaustive.

| Feature | Location (evidence) | Status | Level | Issues |
|---|---|---|---|---|
| Login / logout | `routes/auth.php:21-25,67-68`, `AuthenticatedSessionController` | COMPLETE | 4 | Requires undefined aliases only on other routes |
| Register | `RegisteredUserController.php:28-51` | COMPLETE | 3 | Auto-verifies + auto-activates (`:40-41`) |
| Password reset | `PasswordResetLinkController`, `NewPasswordController` | COMPLETE | 4 | Inertia pages exist but app.blade root not configured |
| Email verification | `CustomVerifyEmailController` | BROKEN | 1 | `MustVerifyEmail` not implemented (`app/Models/User.php:5,11`) |
| Magic link | `MagicLinkController.php:20-113` | PARTIAL | 3 | Route lacks `signed` middleware; shares reset token table |
| Profile | `ProfileController` | COMPLETE | 4 | Tests exist; Inertia root issue |
| RBAC | `App\Models\Role/Permission`, `HasPermissions` | BROKEN | 1 | Spatie not booted; aliases undefined; UI-only |
| Student management | `Modules/Academic/.../StudentController.php` | COMPLETE | 4 | No authz; hardcoded fee matrix `:200-213` |
| Academic subjects/classes | `Modules/Academic/.../SubjectController.php` | PARTIAL | 3 | Missing methods `groups/assignGroups/removeGroup`; unscoped findOrFail |
| Finance — fee CRUD | `Modules/Finance/.../FeeController` etc. | PARTIAL | 3 | No auth middleware; `$request->all()` unvalidated |
| Finance — payments/gateway | `PaymentController`, `OnlinePaymentController` | MISSING | 0 | Methods absent; no gateway calls |
| Finance — reports/forecasting | `ReportController`, `ForecastingController` | MISSING | 0 | Methods absent |
| Finance — reconciliation | `BankReconciliationController` | STUB | 1 | `import/match/updateStatus` absent; view "coming soon" |
| Examination CRUD | `Modules/Examination/.../ExamController.php:12-100` | MOCKED | 2 | Hardcoded demo exams; store/update/destroy no-ops |
| Online exam | `OnlineExamController` | STUB | 1 | Methods return canned JSON; many absent |
| Results/proctoring | `ResultController`, `ProctoringController` | PARTIAL | 2 | Views missing; studentResults missing |
| Attendance (Academic engine) | `Modules/Academic/.../AttendanceController.php` | PARTIAL | 3 | Real marking; missing take/save/bulk methods |
| Attendance (Attendance module) | `Modules/Attendance/.../AttendanceController.php` | STUB | 1 | Empty store/update/destroy; no Models dir |
| Attendance (HR staff) | `Modules/HR/.../AttendanceController.php` | PARTIAL | 3 | Missing destroy/edit/update |
| Timetable generation | `TimetableController.php:217-301`, `Services/TimetableAutoGenerator` | PARTIAL | 3 | Works; approval workflow methods absent |
| Timetable approvals | `Modules/Timetable/routes/web.php:137-141` | MISSING | 0 | No controller methods |
| Rooms/allocations | Hostel + Timetable | DUPLICATED | 2 | Conflicting tables/controllers |
| Library — books | `Modules/Library/.../BookController.php` | PARTIAL | 3 | Real; search route shadowed |
| Library — borrow/return | `BorrowController.php:11-41` | MOCKED | 1 | Hardcoded demo data |
| Library — reports | `Library/.../ReportController.php` | MOCKED | 1 | Hardcoded |
| HR — staff | `Modules/HR/.../StaffController.php` | PARTIAL | 3 | Real CRUD; creates linked User |
| HR — leave/payroll/contracts | HR controllers | PARTIAL | 3 | Many resource methods missing |
| Hostel | `Modules/Hostel/.../*` | COMPLETE | 4 | Most complete module; reports partly unrouted |
| Transport | `Modules/Transport/.../*` | PARTIAL | 3 | Wrong `Academic\app\Models\Student` import; missing views |
| Communication — messages | `Communication/.../MessageController.php` | COMPLETE | 4 | Email+SMS+push real; templates/settings methods missing |
| ChatBot / AI | `Modules/ChatBot/.../*` | COMPLETE | 4 | Hardcoded API key; module API not loaded |
| Portal (student/parent/teacher) | `Modules/Portal/.../PortalController.php` | MOCKED | 2 | Demo data; empty models; wrong imports |
| Settings | `Modules/Settings/.../SettingsController.php` | BROKEN | 3 | 6 missing views; backup stubs |
| Dashboards | `app/Http/Controllers/DashboardController.php` | PARTIAL | 3 | Missing model namespaces masked to 0 |
| Notifications | `Modules/Notification/...` | STUB | 2 | No-op controller; missing views |
| API (v1 module) | `Modules/*/routes/api.php` | DEAD | 0 | Never loaded |
| Mobile API | `Modules/API/routes/mobile.php` | DEAD | 0 | Never loaded; missing controllers |
| Android app | `android-app/` | BROKEN | 0 | Non-compilable |
| SaaS multi-tenancy | `routes/tenant.php`, `config/tenancy.php` | DEAD | 0 | Provider not registered |
| Broadcasting/chat channels | `routes/channels.php` | DEAD | 0 | Broadcast provider commented (`config/app.php:83`) |
| Payments (PayPal/M-Pesa) | Finance settings/tables | MISSING | 0 | No integration |
| Backups | `config/backup.php` | PARTIAL | 2 | Package configured; scheduler not wired |
| Sentry | `config/sentry.php` | STUB | 0 | `return []` |

---

## 5. Critical Gaps

### CRIT-01 — Framework providers disabled; required middleware aliases undefined
- **Feature:** Infrastructure / API auth / RBAC.
- **Files:** `bootstrap/app.php:32-62`, `config/auth.php:38-43`, `app/Models/User.php:11`.
- **Problem:** Sanctum, spatie/permission, tenancy, mail, notification, pagination, broadcast providers are not registered; no `role`/`permission`/`admin` aliases exist.
- **Impact:** `auth:sanctum` throws "guard not defined"; `role:`/`permission:`/`admin` routes 500; API auth impossible; RBAC non-functional.
- **Evidence:** `bootstrap/app.php:32-62` (explicit provider list, module package commented); `config/auth.php:38-43` (web guard only); runtime `sanctum` route matches = 0.
- **Recommended solution:** Either restore nwidart + register Sanctum/Spatie/Tenancy properly via `bootstrap/app.php`/`config/app.php`, or deliberately remove them and implement a custom auth/RBAC consistently; register middleware aliases.

### CRIT-02 — Finance module has no authentication middleware
- **Feature:** Finance.
- **Files:** `Modules/Finance/Routes/web.php:99` (and payment group `:92-97`).
- **Problem:** The `finance` prefix group declares no `middleware()`.
- **Impact:** Fees, payments, taxes, bank accounts/transfers, ledger, finance roles, and finance **settings (gateway secrets)** are reachable unauthenticated (on case-insensitive filesystems where the capital `Routes/` folder loads).
- **Evidence:** only `Route::prefix('finance')->name('finance.')` at `:99`; contrast other modules that use `->middleware(['auth'])`.
- **Recommended solution:** Add `->middleware(['auth'])` (and appropriate permission checks) to the Finance route group; move debug/test routes out of production.

### CRIT-03 — All module API routes are dead; mobile API controllers missing
- **Feature:** API surface.
- **Files:** `routes/modules.php:12`, `Modules/*/routes/api.php`, `Modules/API/routes/mobile.php:5-11`.
- **Problem:** Only `web.php` is loaded per module; `mobile.php` references `Mobile\{Academic,Finance,Notification,Library,Hostel,Transport,Document}Controller` that do not exist (only `Mobile/AuthController.php` exists).
- **Impact:** ~136 module API + ~75 mobile endpoints are unreachable (0 loaded at runtime); Android cannot integrate.
- **Evidence:** runtime route list `api/v1`=0, `mobile/v1`=0; `Modules/API/routes/mobile.php`.
- **Recommended solution:** Decide API strategy; if keeping modules, load `api.php` with correct middleware; implement/remove missing mobile controllers; document endpoints.

### CRIT-04 — Hardcoded live-looking Gemini API key + TLS verification disabled
- **Feature:** ChatBot/AI.
- **Files:** `Modules/ChatBot/Services/GeminiService.php:19,37`.
- **Problem:** `env('GEMINI_API_KEY') ?: 'AIzaSyDk73BVWhZ5AOhkTU4l4Ovzaf3Y08OFu2w'`; `'verify' => false`.
- **Impact:** Secret exposed in VCS; API abuse/cost; MITM on AI calls.
- **Evidence:** lines above.
- **Recommended solution:** Rotate/revoke the key, remove fallback, add `GEMINI_API_KEY` to `.env.example`, enable TLS verification.

### CRIT-05 — Unauthenticated debug and DB-seeding routes
- **Feature:** Debug/infrastructure.
- **Files:** `routes/web.php:7-9` (`/test`), `:78-100` (`/test-transport`), `:103-328` (`/seed-transport`).
- **Problem:** `GET /seed-transport` writes Driver/Vehicle/Route/Trip rows; test routes publish exception traces/schema.
- **Impact:** Public data injection and information disclosure.
- **Recommended solution:** Remove/guard with `auth`+`admin`, or move to console commands.

### CRIT-06 — Inertia root template not configured
- **Feature:** Frontend.
- **Files:** `resources/views/app.blade.php` (no `@inertia`/`@inertiaHead`/`@routes`/`@vite`), `resources/js/app.ts`.
- **Problem:** Controllers using `Inertia::render()` for password reset/confirm/verify cannot mount; Ziggy `route()` has no payload.
- **Impact:** Inertia pages broken; 15 orphan Vue pages unreachable.
- **Recommended solution:** Implement the Inertia root Blade with `@inertia`/`@vite`/`@routes`, or standardize entirely on Blade (decide one).

### CRIT-07 — 78 route actions point to non-existent controller methods
- **Feature:** Multiple modules.
- **Files (representative):** `Modules/HR/routes/web.php` (21), `Modules/Finance/Routes/web.php:88-97,151-159,171-172` (16), `Modules/Timetable/routes/web.php:137-141` (14), `Modules/Core/routes/web.php` (9), `Modules/Portal/routes/web.php` (6), `Modules/Academic/routes/web.php:62-64,130-131` (6).
- **Impact:** Guaranteed 500s on navigation and actions.
- **Recommended solution:** Implement missing methods or remove the routes/menu entries; add a route→method existence test.

### CRIT-08 — 159 controller `view()` references have no Blade file
- **Feature:** Module UI.
- **Files (counts):** Finance 57, Examination 37, Library 19, Academic 11, Settings 6, Transport 6, Document 5, Communication 5, Notification 5, Attendance 3, Core 3, HR 2, Timetable 2.
- **Impact:** Pages 500. Many are naming mismatches (`fee-categories` vs `fee_categories/`).
- **Recommended solution:** Reconcile view names/directories; create missing views or change controllers to existing views.

### CRIT-09 — Migration integrity: duplicate/conflicting migrations and FK ordering
- **Feature:** Database.
- **Files:** duplicate table creations — `rooms` (Hostel vs Timetable), `room_allocations` (Hostel vs Timetable), `settings` (main vs Settings), `leave_types` (main vs HR), `exam_types`, `notifications`, `notification_templates`; duplicate basenames collapse (`2024_06_28_000000_create_settings_table`, `...add_school_id_to_settings_table`, `2025_07_04_164833_create_leave_types_table`, `...add_name_admissionnumber_classid...`); FK ordering — `users.school_id` → `schools` (later), `role_user` → `roles` (a year later).
- **Impact:** Fresh DB may be missing columns silently; conflicting schemas; portability failures.
- **Recommended solution:** Consolidate migrations, fix FK order, ensure unique basenames, add a fresh-migrate CI check.

### CRIT-10 — Any authenticated user can rewrite `.env` (ChatBot admin)
- **Feature:** ChatBot settings.
- **Files:** `Modules/ChatBot/routes/web.php:32-41`, `Modules/ChatBot/Http/Controllers/AdminController.php:178-197`, `ChatBotSettingsController.php:180-203`.
- **Problem:** No admin/permission gate; writes arbitrary keys to `.env` without escaping.
- **Impact:** Env injection, privilege escalation, config tampering.
- **Recommended solution:** Gate behind admin, remove `.env` writes (use DB settings + config cache), validate keys.

### CRIT-11 — Default admin credentials in tracked scripts/seeders
- **Files:** `create_admin_credentials.php:31`, `create_admin_credentials_fixed.php:64`, `setup_database.php:120,138`, `database/seeders/CoreSeeder.php`.
- **Problem:** `admin@dunco.com` / `password` (and teacher/student/parent) tracked in git.
- **Impact:** Full takeover if seed scripts run in production.
- **Recommended solution:** Remove credential scripts from VCS, force password reset on first login, generate random passwords.

### CRIT-12 — Zero-byte model files referenced by code
- **Files:** `Modules/Examination/Models/{Exam,ExamAnswer,ExamAttempt,ExamResult,ExamSchedule,ExamType,ProctoringLog,Question,QuestionCategory}.php`, `Modules/Academic/Models/{Grade,GradingScale}.php`, `Modules/Portal/Models/{Announcement,Message}.php`, `Modules/Finance/Models/MpesaTransaction.php`, `Modules/Settings/Models/Setting.php`; plus the **Attendance module has no `Models/` dir** yet `app/Http/Controllers/DashboardController.php:86` uses `\Modules\Attendance\Models\AttendanceRecord`.
- **Impact:** Fatal class errors wherever referenced; silent zero dashboards.
- **Recommended solution:** Implement models with correct namespaces/tables or remove references.

### CRIT-13 — Email verification non-functional
- **Files:** `app/Models/User.php:5,11` (`MustVerifyEmail` commented), `CustomVerifyEmailController.php:34,39`, `RegisteredUserController.php:40-41` (auto-verify).
- **Impact:** `verified` middleware is a no-op; verification UI does nothing.
- **Recommended solution:** Implement `MustVerifyEmail` or remove the verification flow and middleware consistently.

### CRIT-14 — Dashboards silently show zeros due to missing models
- **Files:** `app/Http/Controllers/DashboardController.php:86,168,239-311`.
- **Problem:** `safeCount/safeSum/safeGet` swallow exceptions for missing classes (`Modules\Attendance\Models\AttendanceRecord`, `Modules\Library\Models\Book`).
- **Impact:** Misleading operational data with no error surfaced.
- **Recommended solution:** Fix namespaces/models; log + surface failures instead of masking.

---

## 6. High Priority Gaps

### HIGH-01 — Authorization is UI-only; unscoped record lookups (IDOR)
- **Files:** `Modules/Academic/.../StudentController.php:239,249,293,501,516,548`; `SubjectController.php` (many `findOrFail($id)`); `Modules/Library/.../BookController.php:96-149`; `Modules/Examination/.../ResultController.php:110,135`; `Modules/Timetable/.../NotificationController.php:23`.
- **Impact:** Any authenticated user can read/modify other schools' or users' records; no policies for any model except an unused `StudentFeePolicy`.
- **Solution:** Introduce policies/gates per model; scope all queries by `school_id`/owner; enforce server-side.

### HIGH-02 — Missing resource/workflow methods (HR, Timetable, Core, Portal, Academic)
- **Files:** HR (`Contract/Leave/Payroll/Attendance/Department/Role/Permission` controllers), `Modules/Timetable/.../TimetableController.php` (approval workflow), `Modules/Core/.../AuditLogController.php` (create/store/edit/update/destroy), `Modules/Portal/.../PortalController.php` (resource), `Modules/Academic/.../SubjectController.php:323-463`.
- **Impact:** Broken CRUD and workflows; 500s.
- **Solution:** Implement or remove; add method-existence tests.

### HIGH-03 — Finance gateway/reporting/reconciliation missing; no real payments
- **Files:** `Modules/Finance/Routes/web.php:88-97,132-134,150-172`; `PaymentController`, `OnlinePaymentController`, `BankReconciliationController`, `ReportController`, `ForecastingController`, `MultiBankController`, `LedgerController`.
- **Impact:** Core finance workflow unusable; no ledger posting; no receipts from payments.
- **Solution:** Implement payment gateways (or explicitly stub behind a feature flag), reports, reconciliation, ledger postings; wrap payment flows in transactions.

### HIGH-04 — Zip Slip on student bulk import
- **File:** `Modules/Academic/Http/Controllers/StudentController.php:308-334`.
- **Impact:** Authenticated path traversal write via crafted zip entries.
- **Solution:** Validate entry names, extract to sandbox, reject `../`.

### HIGH-05 — Global secrets stored plaintext and editable by any verified user
- **Files:** `Modules/Settings/routes/web.php:6-17`, `SettingsController.php:149-190` (`smtp_pass`, `sms_api_key`, `api_token`, `fcm_server_key`, `africastalking_api_key`).
- **Impact:** Secret disclosure/tampering.
- **Solution:** Admin-gate, encrypt at rest (`Crypt`), mask in UI.

### HIGH-06 — Mass assignment via `$request->all()` without validation
- **Files:** `Modules/Finance/.../{Tax,Payment,Fee,FeeType,FeeCategory}Controller`; `Modules/HR/.../PerformanceReviewController.php:34,56`; `Modules/API/Http/Controllers/APIController.php:44-74`.
- **Impact:** Self-assignment of sensitive fields (payment `status`/`reference`, fee `amount`, review `score`), even with `$fillable` guards.
- **Solution:** Add FormRequests/validation; whitelist fields.

### HIGH-07 — Communication provider unregistered; Notification provider no-op
- **Files:** `Modules/Communication/module.json` (`"providers": []`), `Modules/Notification/Providers/NotificationServiceProvider.php` (empty), `NotificationController.php:11` (`notification::index`).
- **Impact:** Module migrations/views/config/API dead; `notification::*` views unresolvable.
- **Solution:** Register providers; load views/migrations; implement Notification controller or remove it.

### HIGH-08 — Duplicate/overlapping modules and conflicting schemas
- **Files:** Attendance (Academic/Attendance/HR), Exam (Academic/Examination), RBAC (Core/HR), Rooms/Allocations (Hostel/Timetable), Reports (Academic/Finance/Library), Notifications (app/Notification/Timetable).
- **Impact:** Conflicting `rooms`/`room_allocations` schemas, unclear canonical logic, three RBAC table schemes.
- **Solution:** Choose canonical implementations; deprecate/remove duplicates; consolidate tables.

### HIGH-09 — Scheduler not wired; no queue jobs
- **Files:** `app/Console/Kernel.php:13-20` (legacy), `bootstrap/app.php` (no `withSchedule`), `routes/console.php:6-8`, no `app/Jobs`.
- **Impact:** Backups and `fees:send-reminders` never run; async work impossible.
- **Solution:** Move schedule to `bootstrap/app.php`/`routes/console.php`; add jobs + worker deployment.

### HIGH-10 — CI and deployment broken/absent
- **Files:** `.github/workflows/ci.yml:49-63` (rewrites non-existent `.env.example` keys → wrong DB name), `.github/workflows/android-build.yml` (JDK 17 vs Java target 21), no Docker/nginx/deploy files.
- **Impact:** CI red; cannot deploy reproducibly despite README claims.
- **Solution:** Fix CI env handling; align Java toolchain; add containerization/deploy docs.

### HIGH-11 — Android app non-compilable
- **Files:** `AppNavigation.kt:8-14` (missing screens), `AuthViewModel.kt:7` (missing use case), `NetworkModule.kt:64-90` (missing APIs), `AndroidManifest.xml` (missing services/resources), fake `google-services.json`.
- **Impact:** Mobile client unusable.
- **Solution:** Implement missing classes/resources or scope down; replace placeholder Firebase config.

### HIGH-12 — Sidebar/menu links to non-existent URIs/routes
- **Files:** `resources/views/components/sidebar.blade.php:6,12,13,19,44,45,53`; `app/Http/Controllers/Api/SidebarController.php:112,325`.
- **Impact:** 404s for Exams, Timetable, Attendance, API, schedules, rooms, hostel allocations.
- **Solution:** Reconcile menu hrefs with actual routes; add a link-check test.

### HIGH-13 — Route duplication and double name prefixes
- **Files:** `Modules/Academic/routes/web.php:12,128` (`academic.academic.*`); `academic.academic.subjects.resources.*`; `'academic.'` name collision `:122-124`; duplicated web registration via `routes/modules.php` + module providers; shadowed static routes (e.g., `subjects/{id}` before `subjects/export`).
- **Impact:** Unreachable endpoints, confusing names, potential conflicts.
- **Solution:** Normalize prefixes/names; register routes once; order static before dynamic.

### HIGH-14 — Inertia/Ziggy not bootstrapped; orphan frontend pages
- See CRIT-06. Impact: entire Vue side unreachable.

### HIGH-15 — Module migration/view loading is case-sensitive and partial
- **Files:** `app/Providers/AppServiceProvider.php:101,105` (`Database/Migrations` capital), module providers using `Routes/` capital paths; modules missing `loadMigrationsFrom` (Core, HR, Hostel, Portal, Settings, Notification, Library).
- **Impact:** On Linux, module migrations/views may not load; 37 migrations could be skipped.
- **Solution:** Normalize directory casing; load migrations/views explicitly; test on Linux CI.

---

## 7. Medium Priority Gaps

### MED-01 — No validation / API Resources / consistent error envelopes / transactions
- **Files:** API controllers (raw models/paginators), `APIController.php`, attendance log getters; no `DB::transaction` in API controllers.
- **Impact:** Mass assignment, inconsistent clients, partial writes.
- **Solution:** FormRequests, API Resources, standard envelope, transactions.

### MED-02 — Placeholder / fake UI
- **Files:** `resources/views/dashboard/{finance,hr,hostel,library,transport}.blade.php:20` ("under development"), `dashboard/{student,parent,default}.blade.php` (17 "coming soon" alert buttons), disabled export buttons in Timetable reports, dead `href="#"` across Examination/Portal/Attendance.
- **Impact:** Misleading UX.
- **Solution:** Implement or hide; remove alert stubs.

### MED-03 — `.env.example` incomplete; Sentry stub
- **Files:** `.env.example` (missing `OPENAI_*`, `GEMINI_API_KEY`, `CHATBOT_ENABLED`, `BACKUP_*`, `PERFORMANCE_*`), `config/sentry.php` (`return []`).
- **Impact:** Misconfiguration; no error monitoring.
- **Solution:** Document all env vars; configure Sentry DSN.

### MED-04 — Security hardening gaps
- **Files:** no `config/cors.php` (wildcard default), `app/Http/Middleware/SecurityHeaders.php:17` (`unsafe-inline`), `config/session.php:172` (`SESSION_SECURE_COOKIE` unset), Android `NetworkModule.kt:25-27` (BODY logging), `.env` `APP_DEBUG=true`.
- **Impact:** Reduced defense-in-depth; token leakage in logs.
- **Solution:** Tighten CORS/CSP/cookies; disable verbose logging in release.

### MED-05 — Missing upload validation
- **Files:** `Communication/MessageController.php:106-113,372-379,450-457,521-529`, `Academic/StudentController.php:186-196`, `SubjectResourceController.php:40`.
- **Impact:** Malicious file upload to public disk.
- **Solution:** Validate mime/size; store privately; scan.

### MED-06 — No shared loading/empty/error UI; hand-rolled pagination; a11y gaps
- **Files:** `Books/Index.vue:62-67`, Library components, `SmartSearchBar.vue:96-114` (swallows errors).
- **Impact:** Poor UX and accessibility.
- **Solution:** Shared components; consistent states; a11y labels.

### MED-07 — Three RBAC schemes coexist
- **Files:** custom `role_user`/`permission_role`, HR `role_permission`, Spatie `role_has_permissions`/`model_has_*`.
- **Impact:** Confusion, inconsistent seeding.
- **Solution:** Standardize on one.

### MED-08 — N+1 / eager-loading not systematically addressed
- **Files:** numerous controllers returning paginated models with relations (e.g., `APIController.php:161-190`), dashboards with many counts.
- **Impact:** Performance degradation at scale.
- **Solution:** Eager load, `withCount`, indexes.

### MED-09 — `.env`/debug exposure in dev only but risky if copied
- **Files:** `.env` `APP_DEBUG=true`, `APP_ENV=local`.
- **Solution:** Production profile; never copy dev `.env`.

---

## 8. Low Priority Gaps

- **LOW-01** `console.log` in shipped components: `resources/js/Layouts/LibraryLayout.vue:58`, `Modules/Core/.../users/edit.blade.php:162`, `roles/edit.blade.php:134-181`.
- **LOW-02** Wrong model namespace: `Modules/Transport/Models/Trip.php:54` (`Modules\Academic\app\Models\Student`); `Modules/Timetable/routes/web.php:92`.
- **LOW-03** Unused/imported-but-unrouted: `MpesaCallbackController` (`routes/web.php:22`); unused private helpers `FinanceController.php:63-295`.
- **LOW-04** Commented-out registrations: `Modules/Library/Providers/RouteServiceProvider.php:61-67`; `Modules/Examination/Providers/ExaminationServiceProvider.php:197-202`.
- **LOW-05** Stale root scripts: `fix_migrations*.php`, `setup_database.php`, `test_*.php`, `seed_database.php` (0 bytes); hardcoded creds.
- **LOW-06** Orphan views/pages: `resources/views/dashboard.blade.php`, `finance/index.blade.php`, 15 orphan Vue pages.
- **LOW-07** `Broken <inertia-link>` usage (no import/registration): `LibraryLayout.vue:9-32`, Library pages, `Library/Dashboard.vue`.
- **LOW-08** Gest framework boilerplate counted as features in docs (e.g., Breeze `Dashboard.vue:24`).
- **LOW-09** `composer.json` wildcard constraints (`*`) reducing reproducibility.
- **LOW-10** `tests/Pest.php:44-47` leftover `something()` helper.

---

## 9. Missing Features

- Payment gateway integration (PayPal, M-Pesa STK/C2B, bank transfer) — only settings/tables exist.
- Ledger posting / double-entry accounting; receipt generation from payments.
- Financial reports (fee collection, outstanding, income/expense, forecasting).
- Bank reconciliation import/match.
- Examination CRUD against DB; online exam taking; grading; result publishing; proctoring persistence.
- Timetable approval/publish workflow.
- Library borrow/return and reports (currently mocked).
- Notification management UI (templates/settings/manage).
- Settings pages: general/academic/finance/notifications/security/backup.
- Portal communication/library/finance/LMS real data.
- Mobile app screens/services and the mobile API controllers.
- Multi-tenancy activation (routes/config inert).
- Broadcasting/chat channels.
- Scheduled backup/fee reminders (scheduler unwired).
- API documentation output (Scribe not generated).
- Production deployment artifacts (Docker/nginx/supervisor).

---

## 10. Partially Implemented Features

- **Finance CRUD** — basic fee/fee-type/category/payment CRUD exist but unauthenticated, unvalidated; gateway/reports/recon missing.
- **Attendance** — real engine in Academic; module controller stubbed; HR staff present but incomplete.
- **Timetable** — generation/conflict detection real; approval workflow missing; some views/namespaces broken.
- **Library** — books real; borrow/reports mocked; many views missing.
- **HR** — staff/leave-types/performance partial; many resource methods missing.
- **Transport** — CRUD real; wrong import; missing views; no passenger controller.
- **Settings** — resource + global/per-school real; 6 views missing; backup stubs; AJAX stubs.
- **Portal** — some real data (dashboard/academics) but finance/LMS/transport/welfare demo.
- **Dashboards** — role routing correct; data masked to zero.
- **API** — base `api.php` loaded but auth broken; module APIs dead.
- **Email verification / magic link** — flows present, enforcement/security incomplete.
- **Backups** — configured, not scheduled.

---

## 11. Broken Features

- Inertia-rendered pages (password reset/confirm/verify) — root template missing directives.
- `auth:sanctum` protected endpoints — guard undefined.
- Any route using `role:`/`permission:`/`admin` aliases — alias undefined → 500.
- 78 routes with missing controller methods.
- 159 routes rendering missing Blade views.
- Sidebar links with invalid URIs.
- `Library` Vue navigation (`<inertia-link>` unregistered).
- Android build.
- CI workflows.
- `notification::*` view rendering (namespace not registered).
- `Communication` module views/migrations (provider unregistered).
- `Transport` trip create (wrong namespace import).
- `/apis/stats` (`Language::count()` without import).
- ChatBot `getStatistics()/testChatBot()` (undefined `$this->openAIService`).

---

## 12. Stub / Placeholder / Mock Audit

| Location | Type | Detail |
|---|---|---|
| `Modules/Finance/Services/FinanceNotificationService.php:54-73` | Mock | SMS/Email/WhatsApp only `Log::info` |
| `Modules/ChatBot/Services/DocumentService.php:139,150,170` | Stub | PDF/Word/OCR return placeholder strings |
| `Modules/ChatBot/Services/GeminiService.php:19` | Hardcoded secret | API key fallback |
| `Modules/ChatBot/Services/ConversationService.php:36,59` | Mock | Mock conversation on DB error |
| `Modules/ChatBot/Services/ChatBotService.php:181,197,203` | Bug | Uses undefined `$this->openAIService` |
| `Modules/Timetable/.../RoomAllocationController.php:137-144` | Stub | "not implemented yet" |
| `Modules/Academic/.../SubjectController.php:323-328` | Mock | `rand()` + "Jane Doe" |
| `Modules/Academic/.../SubjectController.php:438-463` | Stub | import/export/analytics "not implemented" |
| `Modules/Attendance/.../DashboardController.php:45` | Stub | `$defaulters = []` |
| `Modules/HR/.../LeaveController.php:51-81` | Stub | TODO notifications/balance |
| `Modules/Examination/.../ExamController.php:12-100` | Mock | Hardcoded exams; no-ops |
| `Modules/Examination/.../OnlineExamController.php:25-88` | Stub | Canned JSON, fake downloads |
| `Modules/Library/.../BorrowController.php`,`ReportController.php` | Mock | Hardcoded collections |
| `Modules/Portal/.../PortalController.php:258-291,396-422` | Mock | Demo fees/payments/LMS |
| `Modules/Finance/.../FinanceController.php:25,63-295` | Mock | Hardcoded zeros; dead helpers |
| `Modules/Settings/.../SettingsController.php:280-308` | Stub | AJAX/backup stubs |
| `Modules/Notification/.../NotificationController.php:20-62` | Stub | No-op redirects |
| `resources/views/dashboard/{student,parent,default}.blade.php` | UI stub | 17 "coming soon" alerts |
| `resources/views/dashboard/{finance,hr,hostel,library,transport}.blade.php:20` | UI stub | "under development" |
| `resources/views/modules/attendance/mark.blade.php:12-48` | UI stub | TODO populate |
| `resources/views/dashboard.blade.php:9` | Mock | "Dummy trend data" |
| `Modules/Examination/.../exams/index.blade.php:559` | Mock | Sample rows fallback |

---

## 13. Duplicate Code Audit

| Duplicate | Instances | Canonical recommendation |
|---|---|---|
| Attendance controllers | Academic, Attendance, HR | Consolidate on Academic engine + HR staff extension; remove module stub |
| Exam controllers/models | Academic, Examination | Choose Examination module as canonical |
| RBAC controllers/models | Core, HR (+Spatie) | Keep Core + custom models; remove HR duplicate |
| Rooms/RoomAllocation | Hostel, Timetable | Keep Hostel for hostel rooms; Timetable for scheduling rooms (rename tables) |
| Reports | Academic, Finance, Library | Keep per-domain; share a base exporter |
| Notifications | app, Notification, Timetable/Api, Communication | Consolidate on app-level + Communication transport |
| Settings | Finance, Settings | Keep Settings module |
| Dashboards | app, Attendance, Timetable/Api | Keep app-level role dashboards |
| Vue pages | `Index.vue` ×4, `Edit.vue` ×4, `Create.vue` ×3, `Dashboard.vue` ×2 | Shared generic CRUD components |
| Model trees | `app/Models/Modules/Library/{Models,app/Models}` | Single canonical namespace |
| `$request->all()` CRUD | Finance/HR/API | Shared FormRequests |

God classes: `app/Http/Controllers/Api/SidebarController.php` (1010), `ChatBotController.php` (772), `Communication/MessageController.php` (711), `TimetableController.php` (572), `PortalController.php` (557), `Academic/StudentController.php` (530), `API/Mobile/AuthController.php` (525).

---

## 14. Route Audit

- **Total at runtime:** 853 (849 previously reported; includes framework). 134 without auth/sanctum middleware.
- **Broken (missing methods):** 78 (see CRIT-07 for representative list).
- **Dead/unloaded:** all `Modules/*/routes/api.php` and `Modules/API/routes/mobile.php`; `routes/channels.php`; `routes/tenant.php`.
- **Duplicate names:** `academic.` ×3 (`Modules/Academic/routes/web.php:122-124`); double prefixes `academic.academic.attendance.*`, `academic.academic.subjects.resources.*`.
- **Conflicting/static-shadowed:** `subjects/{id}` shadows `subjects/export|analytics`; `questions/{question}` shadows `questions/export`; `schedules/{schedule}` shadows `schedules/timetable`; `results/{result}` shadows `results/analytics`; `rooms`/resource shadows; `books/{book}` shadows `books/search`; `documents/{document}` shadows `documents/upload|manage`; `apis/{api}` shadows `apis/manage`.
- **Privileged but unauthenticated:** entire Finance group; test/seed routes.
- **Wrong HTTP semantics:** test/debug routes performing writes via GET.
- **Case-sensitivity hazard:** `Modules/Finance/Routes/web.php` (capital `Routes`) loaded only on Windows.

---

## 15. Database Audit

- **Migrations:** 193 files; 176 `Schema::create`; **169 unique tables**; 7 duplicated table creations; several duplicate basenames (silently skipped).
- **Duplicated/conflicting tables:** `rooms`, `room_allocations`, `settings`, `leave_types`, `exam_types`, `notifications`, `notification_templates`.
- **FK ordering hazards:** `users.school_id`→`schools` (later); `role_user`→`roles` (a year later); same-timestamp collision `2024_06_28_100000`.
- **Missing FKs/indexes:** entire Finance and HR table sets; `leave_requests`, `timetables`, `books`, `grading_scales`, `fee_configurations`.
- **Empty models:** 15 (see CRIT-12).
- **Broken relationships:** `App\Models\User::permissions()` pivot wrong (`app/Models/User.php:85`); `MessageThread::hasMany(Message,'thread_id')` with no `thread_id`; `Trip::belongsToMany('Modules\Academic\app\Models\Student')` invalid.
- **Missing tables for models:** `message_threads`, `message_participants`, `message_statuses`, `message_attachments`, Portal `announcements`.
- **Factories:** only `UserFactory`; 9+ `HasFactory` models without factories.
- **Seeders:** `CoreSeeder` (school, ~55 roles, ~70 permissions, admin, settings) effectively required; `SystemPermissionsSeeder` (~300 perms) required for gates; inconsistent Spatie/custom usage; `EnsureTimetableViewPermissionSeeder` silently no-ops (role name mismatch).
- **Soft deletes mismatch:** `staff_attendance_records` has `softDeletes()` but model lacks trait; Examination tables have `deleted_at` but empty models.
- **Case path:** module `database/migrations` vs provider `Database/Migrations`.

---

## 16. Authentication Audit

- **Scaffold:** custom Breeze-like, not Breeze/Fortify/Jetstream. Mixed Blade/Inertia rendering.
- **Working:** login (throttled `routes/auth.php:25`, `LoginRequest.php:60-84`), logout, register, password reset (hashed, 60-min), password change, profile.
- **Broken:** email verification (`MustVerifyEmail` absent); `verified` middleware no-op; registration auto-verifies/auto-activates.
- **Weak:** magic link route lacks `signed`; shares `password_reset_tokens` table with reset.
- **API auth:** non-functional (sanctum guard undefined; `User` lacks `HasApiTokens`).
- **Status handling:** `CheckUserActive` works (web group); `api` group lacks it.
- **Middleware:** global `SecurityHeaders`/`ForceHttps`; web `ClearPermissionCache`, `CheckUserActive`, `PerformanceMonitor`; several middleware unregistered (`SetLocale`, `LogUserActions`, `HandleInertiaRequests`, `CheckPermission`).

---

## 17. Authorization Audit

- **Model:** custom `Role`/`Permission` + `HasPermissions`; `config/permission.php` points at Spatie; Spatie not booted → mismatch.
- **Policies:** only `StudentFeePolicy` (registered, never invoked). No policies for other models.
- **Enforcement:** mostly UI (`NavigationHelper`, `@role`); server-side checks only in `Communication/MessageController.php:470-553`, `Academic/OnlineClassController.php:168-185`, `Academic/SubjectFeedbackController.php:44`.
- **Aliases:** `role:`/`permission:`/`admin` undefined → cannot enforce.
- **In-method middleware:** `AuditLogController.php:13`, `TimetableController.php:219+`, `CustomVerifyEmailController.php:18-20` — ineffective (must be constructor).
- **IDOR:** widespread unscoped `findOrFail` (see HIGH-01).
- **Privilege escalation vectors:** `.env` write (CRIT-10); unauthenticated Finance (CRIT-02).

---

## 18. Frontend Audit

- **Inertia:** 5 `Inertia::render()` calls (all Auth/Profile), all targets exist; **root template not configured** → cannot mount. Modules have zero Inertia renders.
- **Orphan pages:** 15 Vue pages unreferenced (Welcome, Dashboard, Books, Library, Auth/Login, Auth/Register).
- **Blade:** ~44 views at root + module views; **159 controller view refs missing**.
- **Dead/fake UI:** placeholder dashboards, 17 "coming soon" buttons, disabled exports, dead `href="#"`, hardcoded stat (`dashboard/parent.blade.php:74`).
- **Broken components:** `<inertia-link>` unregistered in Library Vue pages.
- **Forms:** 34 `route('...')` names in views resolve to undefined routes (double prefixes, missing names).
- **Gaps:** no shared pagination/loading/empty/error components; error swallowing in `SmartSearchBar.vue`; a11y (icon-only actions, `onclick` links).

---

## 19. Admin Panel Audit

- **Navigation:** `resources/views/layouts/app.blade.php` and `resources/views/components/sidebar.blade.php`; JSON API `SidebarController.php`.
- **Findings:** 118 `route()` names resolve, but sidebar has invalid **URIs** (Exams `/examinations`, Timetable `/timetable`, Attendance `/attendance`, API `/api`, schedules, rooms, allocations) → 404.
- **Undefined route names in SidebarController:** `academic.attendance.index` (actual `academic.academic.attendance.index`), `hr.attendance.index` (actual `attendance.index`).
- **Menu items whose controller method is missing** → 500 (chatbot, examination student/teacher/admin, notification.manage, finance reports/payment).
- **Duplicate admin resources:** Core vs HR Roles/Permissions; Finance vs Settings.
- **Missing settings pages:** general/academic/finance/notifications/security/backup.
- **Debug endpoints:** `/debug/permissions` gated by `auth` only.

---

## 20. API Audit

- **Loaded:** only base `routes/api.php` (21 endpoints: user, sidebar ×5, attendance ×15) — but all fail auth (`sanctum` undefined).
- **Not loaded:** ~136 module v1 endpoints + ~75 mobile endpoints.
- **Missing controllers:** `Modules/API/Http/Controllers/Mobile/{Academic,Finance,Notification,Library,Hostel,Transport,Document}Controller`; `Academic/Api/StudentApiController` (0 bytes).
- **Missing methods:** `APIController::manage`; resource methods in `HRController`, `TransportController`, `TimetableController`, `PortalController`, `MessageController`; 17 `OnlineExamController` methods.
- **Validation:** absent on many endpoints; `APIController::store/update` echoes `$request->all()`.
- **Authorization:** most endpoints unscoped; biometric/QR/face endpoints accept arbitrary student/parent IDs.
- **Serialization:** raw models/paginators; no API Resources; inconsistent envelopes; no transactions.
- **Docs:** `config/scribe.php` filters `v1` but loaded routes are unversioned → docs would be empty/misleading; no generated docs.
- **Consumers:** Blade JS calls dead `/api/v1/*`; Android calls dead `/api/mobile/v1/*`; no `resources/js` consumers.

---

## 21. Security Audit

### CRITICAL
- **S-C1** Finance routes unauthenticated — `Modules/Finance/Routes/web.php:99`. Reachable.
- **S-C2** Unauthenticated seeding/info routes — `routes/web.php:7-9,78-100,103-328`. Reachable.
- **S-C3** Hardcoded Gemini key + TLS disabled — `Modules/ChatBot/Services/GeminiService.php:19,37`.
- **S-C4** Any user rewrites `.env` — `Modules/ChatBot/routes/web.php:32-41`, `AdminController.php:178-197`.
- **S-C5** Tracked default admin credentials — `create_admin_credentials*.php`, `setup_database.php`, `CoreSeeder.php`.

### HIGH
- **S-H1** Zip Slip — `Academic/StudentController.php:308-334`.
- **S-H2** Plaintext editable global secrets — `SettingsController.php:149-190`.
- **S-H3** Mass assignment via `$request->all()` — Finance/HR/API controllers.
- **S-H4** Systemic missing authorization on module routes (auth-only).

### MEDIUM
- **S-M1** Unvalidated uploads to public disk — Communication/Academic.
- **S-M2** Android logs full HTTP bodies (tokens) — `NetworkModule.kt:25-27`.
- **S-M3** Secrets/passwords in logs — `CardPaymentService.php:25`, `Core/UserController.php:112`.
- **S-M4** Public debug routes leak traces/schema — Finance/Timetable test routes.
- **S-M5** `APP_DEBUG=true` in shipped `.env` (untracked).
- **S-M6** Weak CSP (`unsafe-inline`) — `SecurityHeaders.php:17`.
- **S-M7** Session cookie hardening off — `config/session.php:172`.

### LOW
- **S-L1** CORS wildcard default (no `config/cors.php`).
- **S-L2** No trusted-proxy config.
- **S-L3** `/debug/permissions` not admin-gated.
- **S-L4** Fake `google-services.json` tracked.
- **S-L5** Wildcard composer constraints.
- **S-L6** Unescaped `{!! json_encode(...) !!}` in inline JS (low/unproven).
- **S-L7** Static `exec()` for `composer dump-autoload`.

### Injection
- SQL injection: **none exploitable found** (raw fragments use whitelisted/hardcoded values).
- No `eval`/`unserialize`/`shell_exec`/`proc_open` with user input.
- No `{!! !!}`/`v-html` sinks with user-controlled input (confirmed message rendering uses `e()`).
- Path traversal: **Zip Slip (S-H1)**.

### Secrets
- Gemini key in `GeminiService.php:19`.
- Default admin password in tracked scripts/seeders.
- SMTP/SMS/payment tokens stored plaintext in DB (`s_settings`).
- `.env` is gitignored (not committed).

---

## 22. Performance Audit

- **N+1 risk:** paginated models with relations returned raw (e.g., `APIController.php:161-190`); dashboards execute many independent counts.
- **Missing indexes:** Finance/HR tables; most FK columns.
- **Heavy dashboard queries:** `DashboardController` multiple `count`/`sum`; masked on failure.
- **No caching:** `CACHE_STORE=database`; no query/response caching of dashboards.
- **Scheduler polling / no queue:** long-running work would be synchronous.
- **Frontend bundle:** 233 KB app chunk (acceptable); Library pages orphaned.
- **Polling:** sidebar update mechanism (`SidebarController::triggerUpdate`) — verify frequency.
- **Severity:** mostly MEDIUM; no single catastrophic query identified, but absent indexes + no eager loading will degrade at scale.

---

## 23. Testing Audit

- **Total:** 25 test cases — auth (login/registration/email-verification/password-confirmation/reset/update), profile, two boilerplate `ExampleTest`.
- **Real:** Auth/Profile Breeze-derived tests have meaningful assertions.
- **Missing:** RBAC/permissions, API, module routes, attendance/biometric, finance, academic CRUD, ChatBot, tenancy, mobile, frontend.
- **Runnability:** uncertain — `AppServiceProvider` loads module migrations; if MySQL-specific DDL exists, sqlite `:memory:` Feature tests fail. `UNVERIFIED`.
- **CI tests:** workflow likely fails before tests due to DB name mismatch.
- **README claim of "85% coverage" is false.**

---

## 24. Configuration / Deployment Audit

- **`.env.example`:** stock; missing `OPENAI_*`, `GEMINI_API_KEY`, `CHATBOT_ENABLED`, `BACKUP_*`, `PERFORMANCE_*`, `AWS_URL/ENDPOINT`, `ASSET_URL`; README mentions non-existent `MAIL_ENCRYPTION`, `SMS_*`, `MPESA_*`.
- **Sentry:** stub config; needs DSN.
- **Scheduler:** unwired (legacy Kernel only).
- **Queues:** `database` driver; no jobs/worker deployment.
- **CI:** Laravel job DB-name bug; Android job JDK mismatch; `npm test` absent.
- **Deployment:** no Docker/nginx/supervisor/Procfile despite README "Docker Deployment".
- **Storage/mail/cache:** functional defaults; not production-hardened.

---

## 25. Documentation Audit

- **README:** claims Laravel 10/PHP 8.1 (actual 12/8.2); documents API endpoints that don't exist; references non-existent commands (`make:test-user`, `db:optimize`, `performance:monitor`, `create_chatbot_tables.php`); claims 85% coverage and completed production checklist (CI broken, scheduler dead, no deploy).
- **Module architecture doc:** does not reflect disabled providers.
- **GITHUB_SETUP_GUIDE / SYSTEM_STATUS_REPORT:** stale paths/claims (e.g., nwidart "removed" though still required).
- **ANDROID_APP_SUMMARY / BUILD_STATUS:** claim production-ready; app cannot compile.
- **SIDEBAR_UPDATE_IMPLEMENTATION:** mostly accurate; references absent `/test-sidebar-update`.

---

## 26. End-to-End Workflow Audit

| # | Workflow | Status | Level | Break point |
|---|---|---|---|---|
| 1 | Authentication | COMPLETE | 4 | Verification no-op |
| 2 | Student management | COMPLETE | 4 | No authz; hardcoded fees |
| 3 | Finance | BROKEN | 3 | Gateway/reports/recon methods missing; unauthenticated |
| 4 | Examination | MOCKED | 3 | Controllers hardcode data; views missing |
| 5 | Attendance | PARTIAL | 3 | Module stub; missing methods/views |
| 6 | Timetable | BROKEN | 3 | Approval methods missing; namespace/views |
| 7 | Library | MOCKED | 3 | Borrow/reports hardcoded; views missing |
| 8 | HR | PARTIAL | 3 | Resource methods missing |
| 9 | Hostel | COMPLETE | 4 | Strongest workflow |
| 10 | Transport | PARTIAL | 3 | Wrong import; views missing |
| 11 | Communication | COMPLETE | 4 | templates/settings broken; provider unregistered |
| 12 | ChatBot / AI | COMPLETE | 4 | Hardcoded key; API not exposed |
| 13 | Portal | MOCKED | 2 | Demo data; empty models; wrong imports |
| 14 | Settings | BROKEN | 3 | Missing views; backup stubs |
| 15 | Dashboards | PARTIAL | 3 | Missing models → zeros |
| 16 | Notifications | STUB | 2 | No-op controller; missing views |

**Closeness ranking (most→least working):** Hostel, Auth, Student, Communication, ChatBot, Attendance, HR, Transport, Library, Examination, Timetable, Finance, Dashboards, Settings, Portal, Notifications.

---

## 27. Technical Debt

- Disabled module/provider architecture with ad-hoc shims (`AppServiceProvider` manual view/migration loading).
- Three parallel RBAC schemes; Spatie configured but unused.
- 78 dead route actions and 159 dead view references.
- 15 empty models; duplicated model trees.
- Duplicate/conflicting migrations; FK ordering hazards.
- God classes (SidebarController 1010 lines) and duplicated controllers.
- Large volume of mock/stub code presented as features.
- Stale root scripts and build artifacts (`dist/`, `dist_upload/`, `check-zip/`) tracked in the repo.
- Documentation diverging from implementation.
- Non-compilable mobile app, broken CI.

---

## 28. Recommended Architecture Improvements

1. **Decide the module strategy.** Either (A) properly enable nwidart (register the package + module providers, load `web.php` **and** `api.php`, views, migrations, events, commands), or (B) remove nwidart and use plain Laravel namespaces. Do not keep the current half-disabled hybrid.
2. **Single RBAC stack.** Standardize on one of: `spatie/laravel-permission` (boot it) or the custom models — never both. Register middleware aliases.
3. **Single frontend strategy.** Either complete the Inertia setup (root `app.blade.php` with `@inertia`/`@vite`/`@routes`) or standardize on Blade. Eliminate orphan pages.
4. **API strategy.** Enable Sanctum properly; version and load module APIs; implement API Resources, FormRequests, transactions, consistent envelopes; generate Scribe docs.
5. **Consolidate duplicate domains** (Attendance/Exam/RBAC/Rooms/Notifications) to one canonical implementation each.
6. **Database cleanup:** unique migration basenames, correct FK order, indexes on FKs, resolve conflicting tables, add all models/factories.
7. **Secure-by-default routing:** all non-public routes behind `auth`, sensitive ones behind permissions; remove debug/seed routes.
8. **Wire the scheduler and queues** in Laravel 12 (`bootstrap/app.php`/`routes/console.php`), add jobs and worker deployment.
9. **Hardening:** CORS allowlist, strong CSP, secure cookies, secret encryption, upload validation, remove hardcoded secrets, rotate keys.
10. **CI/CD:** fix Laravel + Android workflows, add a fresh-migrate check, containerize, document deployment.
11. **Testing pyramid:** feature tests for every module workflow, RBAC, and API; policy tests.
12. **Repository hygiene:** remove tracked build artifacts and one-off scripts; expand `.gitignore`.

---

## 29. Final Readiness Assessment

**Classification: DEVELOPMENT READY (barely) — NOT TESTING READY, NOT BETA, NOT PRODUCTION READY.**

Rationale:
- The app boots and serves authentication + a few module screens when run on a Windows/case-insensitive filesystem with SQLite (`/` and `/login` return 200).
- It is **NOT testing-ready**: no module/business tests, CI red, many routes 500, no API docs.
- It is **NOT beta-ready**: core finance/exam workflows broken or mocked, Inertia frontend non-functional, authorization largely absent.
- It is **NOT production-ready**: multiple CRITICAL security holes (unauthenticated Finance, `.env` rewrite, hardcoded live key, default admin creds), no deployment artifacts, scheduler/queues unwired, Linux case-sensitivity hazards.

---

# FINAL PHASE-BY-PHASE IMPLEMENTATION PLAN

> Dependency-aware. Gap IDs reference sections above. This plan intentionally starts with security and data integrity before features, and defers UI polish.

---

## PHASE 0 — Baseline & Safety

### Objective
Lock in a stable, reproducible, secure-by-default baseline before any feature work.

### Problems addressed
CRIT-01 (partial), CRIT-05, CRIT-10, CRIT-11, HIGH-10, LOW-05, LOW-06.

### Files/components affected
`bootstrap/app.php`, `routes/web.php`, `.env.example`, `.gitignore`, root one-off scripts, `scripts/`, `Modules/ChatBot/routes/web.php`, seeder scripts.

### Implementation tasks
1. Freeze current state with a tagged commit/branch (`audit-baseline`).
2. Remove/quarantine unauthenticated debug & seed routes (`/test`, `/test-transport`, `/seed-transport`) or convert to `php artisan` commands.
3. Move credential scripts (`create_admin_credentials*.php`, `setup_database.php`, `seed_database.php`) out of the repo root; add to `.gitignore`.
4. Remove tracked build artifacts (`dist/`, `dist_upload/`, `check-zip/`) from VCS (keep on disk if needed).
5. Expand `.env.example` with all referenced keys (AI, SMS, backup, performance, sentry, AWS extras).
6. Add a fresh-migrate smoke script/CI job (sqlite + mysql).
7. Remove ChatBot `.env` write endpoints (temporarily disable) to stop secret tampering.

### Dependencies
None.

### Risk level
Low–Medium (touching routes may expose missing methods, but those are already broken).

### Expected result
A reproducible baseline that no longer exposes debug/seeding endpoints or writes `.env`.

### Verification
- `php artisan route:list` no longer lists `/test*`, `/seed-transport`.
- Fresh clone + `composer install` + `migrate` + `serve` yields 200 on `/` and `/login`.
- `.env.example` superset check vs `grep env(` across `config/`.

### Definition of Done
All debug/seed routes removed; credential scripts untracked; fresh-migrate job green on sqlite; artifact dirs untracked; ChatBot `.env` writes disabled.

---

## PHASE 1 — Critical Security

### Objective
Eliminate exploitable vulnerabilities and secret exposure.

### Problems addressed
CRIT-02, CRIT-03 (auth), CRIT-04, CRIT-10, CRIT-11, HIGH-04, HIGH-05, HIGH-06, S-M1..S-M4, S-H1..S-H3.

### Files/components affected
Finance routes/controllers, ChatBot services/admin, Settings controller, `StudentController.php`, upload handlers, `NetworkModule.kt`, logging.

### Implementation tasks
1. Add `auth` (+ permission) middleware to the entire Finance route group.
2. Rotate and remove the hardcoded Gemini key; add `GEMINI_API_KEY` to env; enable TLS verify.
3. Gate ChatBot admin routes behind admin permission; stop `.env` writes.
4. Admin-gate and encrypt Settings secrets at rest; mask in UI.
5. Fix Zip Slip: validate zip entries, extract to sandbox, reject traversal.
6. Add FormRequest validation to all `$request->all()` CRUD (Finance/HR/API).
7. Add mime/size validation + private storage for uploads.
8. Stop logging tokens/passwords (`CardPaymentService`, `Core/UserController`); set Android logging to `NONE`/`BASIC` in release.
9. Remove debug trace/schema routes.
10. Generate random admin password on seed; force reset; remove tracked creds.

### Dependencies
Phase 0.

### Risk level
Medium (middleware may break currently-public pages → expected; validation may reject existing payloads).

### Expected result
No unauthenticated privileged routes, no hardcoded secrets, no traversal/mass-assignment.

### Verification
- Automated test: unauthenticated requests to finance/upload/admin endpoints return 302/403.
- Secret scanner returns no matches; rotate key confirmed.
- Zip traversal test rejected.

### Definition of Done
All CRITICAL security items closed with tests proving enforcement.

---

## PHASE 2 — Architecture & Provider Bootstrapping

### Objective
Resolve the module/provider/sanctum/spatie inconsistency so routes, views, migrations, and auth boot predictably.

### Problems addressed
CRIT-01, CRIT-03, CRIT-12, HIGH-07, HIGH-14, HIGH-15, MED-07.

### Files/components affected
`bootstrap/app.php`, `config/app.php`, `composer.json` (`dont-discover`), `routes/modules.php`, module providers, `app/Models/User.php`, `config/permission.php`, `config/auth.php`, `resources/views/app.blade.php`.

### Implementation tasks
1. Decide module strategy (recommend re-enable nwidart) and implement it consistently.
2. Register Sanctum (guard + `HasApiTokens`) and, if kept, Spatie Permission (or remove Spatie and use custom only).
3. Register middleware aliases (`admin`, `role`, `permission`, `verified`).
4. Load each module's `api.php` with correct prefix/middleware; register module migrations/views case-correctly.
5. Implement or delete zero-byte models; fix namespaces.
6. Implement the Inertia root template (`@inertia`, `@vite`, `@routes`) if keeping Inertia.
7. Register tenancy provider or remove tenancy + `routes/tenant.php`.

### Dependencies
Phase 0; Phase 1 middleware work aligns here.

### Risk level
High (bootstrapping changes can destabilize the app).

### Expected result
Deterministic boot; middleware aliases resolve; API routes load; models exist; Inertia mounts.

### Verification
- `php artisan route:list` includes `api/v1/*` and `mobile/v1/*` as designed.
- A request to an `auth:sanctum` endpoint returns 401 (not 500).
- `route:list` produces no missing-method errors; a link/method existence test passes.
- Fresh migrate on Linux container succeeds.

### Definition of Done
No undefined guard/alias errors; modules and APIs load; zero-byte models resolved; Linux fresh migrate green.

---

## PHASE 3 — Authentication & Authorization

### Objective
Complete verification and enforce server-side RBAC everywhere.

### Problems addressed
CRIT-13, HIGH-01, HIGH-02 (authz), MED-07.

### Files/components affected
`app/Models/User.php`, `CustomVerifyEmailController.php`, `RegisteredUserController.php`, policies, module controllers, seeders.

### Implementation tasks
1. Implement `MustVerifyEmail` (or remove verification flow + `verified` usage consistently).
2. Fix magic-link `signed` middleware and token table separation.
3. Create policies for core models (User, Student, Fee, Payment, Exam, Result, Subject, Book, Message, Timetable).
4. Replace unscoped `findOrFail($id)` with scoped queries (school/owner) across modules.
5. Replace in-method `$this->middleware(...)` with constructor/route middleware.
6. Standardize on one RBAC scheme; seed roles/permissions idempotently; fix `EnsureTimetableViewPermissionSeeder` role-name mismatch.

### Dependencies
Phase 2 (aliases/Spatie).

### Risk level
Medium–High (authorization can lock out users if seeded wrong).

### Expected result
Verification works; every privileged action is server-authorized; no cross-tenant access.

### Verification
- Feature tests: unauthenticated → 401; non-owner → 403; owner/admin → 200.
- Verification email flow test.

### Definition of Done
Policy/authorization tests pass for all core models; no unscoped lookups on owned data.

---

## PHASE 4 — Database Consolidation

### Objective
Make the schema coherent, portable, and complete.

### Problems addressed
CRIT-09, CRIT-12 (DB side), HIGH-08, MED-08 (indexes).

### Files/components affected
`database/migrations`, `Modules/*/database/migrations`, models, factories, seeders.

### Implementation tasks
1. Resolve duplicate/conflicting tables (`rooms`, `room_allocations`, `settings`, `leave_types`, `exam_types`, `notifications`, `notification_templates`) — rename or merge.
2. Fix FK ordering (`users.school_id`, `role_user.roles`); unique migration basenames.
3. Add missing FKs/indexes (Finance/HR).
4. Add missing tables for models; fix broken relationships.
5. Add factories for `HasFactory` models; complete `UserFactory`.
6. Align soft-deletes/casts with columns.
7. Add a fresh-migrate test on sqlite **and** MySQL in CI.

### Dependencies
Phase 2.

### Risk level
High (schema changes → data migration).

### Expected result
One unambiguous schema; fresh migrate works on both sqlite and MySQL; models map to tables.

### Verification
`migrate:fresh --seed` on sqlite + MySQL; `migrate:status` no pending/duplicates; relationship tests.

### Definition of Done
Fresh migrate + seed green on both drivers; no orphan models; FK/index checks pass.

---

## PHASE 5 — Core Backend Features

### Objective
Implement/complete the business-critical workflows.

### Problems addressed
CRIT-07, CRIT-08 (backend), HIGH-02, HIGH-03, Section 10.

### Files/components affected
Finance controllers/services, Examination controllers, Timetable workflow, HR resources, Attendance module, Library borrow/reports, Portal, Settings, Notifications.

### Implementation tasks
1. Finance: implement payment gateway abstraction (or explicit feature-flagged stub), invoices, ledger postings, receipts, reports, bank reconciliation, forecasting — with transactions.
2. Examination: implement CRUD against DB, online exam taking, grading, result publishing, proctoring persistence.
3. Timetable: implement approval/publish/archive methods; fix view namespaces.
4. HR: implement missing resource methods (`show/edit/update/destroy`) and leave/payroll logic.
5. Attendance: implement module controller or remove it (keep Academic engine); add missing methods/views.
6. Library: replace mocked borrow/reports with real logic; add missing views.
7. Settings: create missing views; implement backup endpoints (or remove).
8. Notifications: implement controller + views or consolidate.
9. Create all missing Blade views referenced by controllers (or fix names).

### Dependencies
Phases 2–4.

### Risk level
High (large scope).

### Expected result
Previously broken/mocked workflows functional end-to-end.

### Verification
Feature tests per workflow (create→list→edit→delete, plus domain flows); manual smoke of each module.

### Definition of Done
Zero missing controller methods/views at runtime; each workflow has passing feature tests.

---

## PHASE 6 — APIs & Mobile

### Objective
Deliver a working, secured, documented API and a compilable mobile client.

### Problems addressed
CRIT-03, HIGH-11, MED-01, Section 20.

### Files/components affected
`routes/api.php`, module `api.php`, `Modules/API/routes/mobile.php`, API controllers, `android-app/`.

### Implementation tasks
1. Load module APIs (Phase 2) and implement missing controllers/methods.
2. Introduce API Resources, FormRequests, standard envelopes, pagination/rate limits.
3. Implement missing `Mobile\*Controller`s; add rate limits to public auth endpoints.
4. Generate Scribe docs aligned with loaded routes.
5. Android: implement missing screens/services/resources, replace fake `google-services.json`, align Java/Kotlin toolchain, fix `NetworkModule` compile errors, reduce logging.
6. Add API contract tests.

### Dependencies
Phases 2–5.

### Risk level
High.

### Expected result
Versioned API functional and documented; Android app compiles and authenticates.

### Verification
Postman/feature tests for each endpoint; `gradle assembleDebug` succeeds; docs generated.

### Definition of Done
All documented endpoints pass contract tests; Android debug build succeeds; CI Android job green.

---

## PHASE 7 — Frontend Standardization

### Objective
Make the frontend coherent and functional (Inertia or Blade, one path).

### Problems addressed
CRIT-06, HIGH-12, HIGH-14, MED-02, MED-06, Section 18.

### Files/components affected
`resources/views/app.blade.php`, `resources/js/**`, layouts/sidebar, module views.

### Implementation tasks
1. Implement Inertia root + Ziggy (or remove Inertia and convert the 5 renders to Blade).
2. Reconnect or delete orphan Vue pages; fix `<inertia-link>` usage.
3. Fix sidebar hrefs/route names; add a link-check test.
4. Replace "coming soon"/"under development"/dummy data with real data or remove.
5. Add shared loading/empty/error/pagination components.
6. Fix form → route name mismatches (34).

### Dependencies
Phase 5 (endpoints/views exist).

### Risk level
Medium.

### Expected result
Navigation works; no dead links; consistent states.

### Verification
Link-check test; manual navigation of every menu item; Lighthouse/a11y spot checks.

### Definition of Done
All menu links resolve; zero "coming soon" stubs on primary flows; shared components used.

---

## PHASE 8 — Admin Panel Completion

### Objective
Every admin resource is real, permissioned, and consistent.

### Problems addressed
HIGH-12, Section 19.

### Files/components affected
`layouts/app.blade.php`, `components/sidebar.blade.php`, `SidebarController.php`, Core/HR/Finance/Settings controllers.

### Implementation tasks
1. Reconcile duplicate admin resources (Roles/Permissions/Settings).
2. Implement missing settings pages and admin CRUD.
3. Enforce permissions on every admin route.
4. Add audit logging for admin actions.

### Dependencies
Phases 3, 5, 7.

### Risk level
Medium.

### Expected result
Functional, permissioned admin panel.

### Verification
Admin-only tests; audit log entries created.

### Definition of Done
Every admin menu item → real feature with authorization + audit trail.

---

## PHASE 9 — Validation & Error Handling

### Objective
Consistent, safe validation and error responses.

### Problems addressed
MED-01, Section 13.

### Implementation tasks
1. FormRequests for all write endpoints.
2. Global exception rendering (401/403/404/419/422/429/500) with safe messages.
3. API error envelope standardization.
4. Frontend validation/error surfacing.

### Dependencies
Phases 5–8.

### Risk level
Low–Medium.

### Verification
Tests asserting status codes + payloads; no stack traces in production responses.

### Definition of Done
All write endpoints validated; error contract tests pass.

---

## PHASE 10 — Performance

### Objective
Remove N+1s, add indexes/caching.

### Problems addressed
MED-08, Section 22.

### Implementation tasks
1. Eager-load relations; add `withCount`.
2. Add missing indexes/FKs (tie to Phase 4).
3. Cache dashboards/stats; enable `config:cache`/`route:cache` in prod.
4. Add slow-query logging + budgets.

### Dependencies
Phase 4.

### Verification
Query-count assertions; load test (`scripts/k6-smoke.js`).

### Definition of Done
Key pages under query budgets; k6 smoke within thresholds.

---

## PHASE 11 — Testing

### Objective
Achieve meaningful coverage of critical paths.

### Problems addressed
Section 23.

### Implementation tasks
1. Feature tests: every module workflow, RBAC, API, uploads, payments (mocked).
2. Policy/authorization tests.
3. Fix `AppServiceProvider` test migration loading; ensure sqlite tests run.
4. Add CI test gate.

### Dependencies
Phases 3–9.

### Verification
`php artisan test` green in CI on sqlite + MySQL.

### Definition of Done
Critical-path coverage thresholds met; CI required check.

---

## PHASE 12 — UX / Accessibility / Responsive

### Objective
Usable, accessible UI.

### Problems addressed
MED-06, Section 18.

### Implementation tasks
1. Accessible buttons/labels/contrast; keyboard nav.
2. Responsive fixes; shared empty/loading states.
3. Replace alert-based UX.

### Dependencies
Phase 7.

### Verification
Automated a11y scan + manual keyboard testing.

### Definition of Done
No critical a11y violations on primary screens.

---

## PHASE 13 — Documentation

### Objective
Docs match reality.

### Problems addressed
Section 25.

### Implementation tasks
1. Rewrite README (versions, real endpoints, real setup).
2. Document env vars, deployment, scheduler/queues.
3. Generate API docs.
4. Architecture + module docs.

### Dependencies
Phases 2–9.

### Verification
Docs review; setup steps executed on a clean machine.

### Definition of Done
Single-sourced, accurate docs; documented setup reproduces success.

---

## PHASE 14 — Production Hardening

### Objective
Deployable, observable, hardened.

### Problems addressed
Section 24, S-M5..S-M7, S-L1.

### Implementation tasks
1. Containerization + web server + supervisor for queues.
2. CORS allowlist, strong CSP, secure cookies, trusted proxies.
3. Configure Sentry/Flysystem/backups; wire scheduler.
4. Add health checks + monitoring.

### Dependencies
Phases 1–13.

### Verification
Staging deploy; penetration spot-check; backup/restore drill.

### Definition of Done
Staging deploy fully functional; scheduler/queues/backups verified.

---

## PHASE 15 — Final QA & Release

### Objective
Release readiness validation.

### Problems addressed
All.

### Implementation tasks
1. Full regression + UAT.
2. Load/security re-test.
3. Release checklist + rollback plan.
4. Remove remaining stubs/mocks or document as feature-flagged.

### Dependencies
All prior phases.

### Verification
QA sign-off; zero CRITICAL/HIGH open; CI green; rollback tested.

### Definition of Done
- 0 CRITICAL, 0 HIGH gaps open.
- CI green (Laravel + Android).
- Fresh install, migrate, seed, deploy reproducible.
- All primary workflows pass UAT.

---

## Implementation Order (summary)

1. Phase 0 Baseline & Safety
2. Phase 1 Critical Security
3. Phase 2 Architecture & Provider Bootstrapping
4. Phase 3 Authentication & Authorization
5. Phase 4 Database Consolidation
6. Phase 5 Core Backend Features
7. Phase 6 APIs & Mobile
8. Phase 7 Frontend Standardization
9. Phase 8 Admin Panel Completion
10. Phase 9 Validation & Error Handling
11. Phase 10 Performance
12. Phase 11 Testing
13. Phase 12 UX / Accessibility
14. Phase 13 Documentation
15. Phase 14 Production Hardening
16. Phase 15 Final QA & Release

---

## Audit limitations / UNVERIFIED items

- Runtime behavior was not exhaustively exercised; findings are static-analysis + route enumeration. Items marked UNVERIFIED require runtime testing (e.g., whether specific module migrations produce MySQL-specific DDL that breaks sqlite tests; exact double-registration behavior; whether Finance `Routes/` capital folder loads on the deployment FS).
- Progress/coverage claims in `README.md` were not validated against a coverage tool.
- Dependency CVE scanning was not performed (versions inspected only).
- No files were modified during this audit except prior baseline repairs noted at the top.
