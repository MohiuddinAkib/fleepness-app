<laravel-boost-guidelines>
=== .ai/api-response-standard rules ===

# API Response Standard

## Overview

All API responses MUST follow the unified standard below. No ad-hoc array construction in controllers.

## Output: Eloquent API Resources

- All API responses use **Eloquent API Resources** (`JsonResource` or `ResourceCollection`).
- Every resource file MUST have a `@mixin ModelClass` PHPDoc block for IDE autocomplete.
- Always declare `toArray(Request $request): array` with the typed `Request` parameter and explicit return type.
- Use `$this->getKeyName() => $this->getKey()` for primary keys — never `'id' => $this->id`.
- Use `$this->whenLoaded('relation', fn () => ...)` for all relationships — never eagerly access relations without checking if loaded.
- Use sub-resources for nested models — never inline arrays for related models.

```php
/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
            'order_code' => $this->order_code,
            'seller_orders' => $this->whenLoaded('sellerOrders', fn () => SellerOrderResource::collection($this->sellerOrders)),
            'customer' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
        ];
    }
}
```

## Input: Spatie Laravel Data

- All validated request input MUST use **Spatie Laravel Data** (`Data` subclasses) as controller parameters — no inline `$request->validate()` in controllers.
- Use `#[MapName(SnakeCaseMapper::class)]` on every Data class for consistent snake_case mapping.
- Use Spatie Data validation attributes (`#[Exists]`, `#[Min]`, `#[Rule]`) for simple rules.
- Complex rules (cross-field, ownership checks) go in a static `rules()` method.

```php
#[MapName(SnakeCaseMapper::class)]
class StoreOrderData extends Data
{
    public function __construct(
        #[Exists(DeliveryModel::class, 'id')]
        public readonly int $deliveryModelId,
    ) {}
}
```

## Controller Response Shape

Always return:
```json
{
  "message": "Human-readable description",
  "data": { ... }   // or "order", "cart_item", etc. for named single resources
}
```

- Pagination: return `ResourceClass::collection($paginator)` — Laravel wraps it with `meta` and `links` automatically.
- Created resources: use HTTP 201 (`Response::HTTP_CREATED`).
- Errors: use `abort()`, `abort_unless()`, `abort_if()` with appropriate HTTP status constants.

## What To Avoid

- ❌ `return response()->json(['data' => $model])` — use a resource.
- ❌ `$collection->map(fn($item) => [...])` — use a resource collection.
- ❌ `'id' => $this->id` — use `$this->getKeyName() => $this->getKey()`.
- ❌ `'relation' => $this->relation` — use `$this->whenLoaded(...)`.
- ❌ Inline `$request->validate([...])` in controllers — use a Data class.
- ❌ `Auth::user()` or `Auth::id()` in controllers — use `#[CurrentUser] User $user` parameter injection.

=== .ai/laravel-native-primitives rules ===

# Laravel Native Primitives

Prefer Laravel's built-in model and HTTP primitives over direct property/column access or manual client instantiation. The goal is to use as much of Laravel's native API as possible.

## Eloquent Model Primitives

- Use `$model->getKey()` instead of `$model->id` when referencing a model's primary key.
- Use `$model->getMorphClass()` instead of hardcoding `modelable_type` or referencing the morph class string directly.
- Use `$related->relation()->is($owner)` instead of `$related->foreign_key === $owner->getKey()` for ownership/relation checks.

```php
// ✅ Correct
abort_unless($deviceToken->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

// ❌ Avoid
abort_unless($deviceToken->user_id === $user->getKey(), HttpResponse::HTTP_FORBIDDEN);
```

## HTTP Status Constants

Always import `Symfony\Component\HttpFoundation\Response` aliased as `HttpResponse` for status code constants in controllers. Never use bare integers for HTTP status codes.

```php
use Symfony\Component\HttpFoundation\Response as HttpResponse;

return Response::json($data, HttpResponse::HTTP_CREATED);
abort_unless($condition, HttpResponse::HTTP_FORBIDDEN);
```

## Migration Foreign Keys

Always use `$table->foreignIdFor(Model::class)->constrained()->cascadeOnDelete()` instead of `$table->foreignId('user_id')->constrained()->cascadeOnDelete()`. This ties the column name to the model class, avoiding typos and keeping migrations consistent with the actual model.

```php
// ✅ Correct
$table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

// ❌ Avoid
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
```

## HTTP Client

- Use `Http::macro()` to integrate third-party API services rather than instantiating custom HTTP clients manually.

## Eloquent Relation Methods

Always include both an explicit PHP return type and a PHPDoc `@return` block with full generics on every relation method. Use `$this` as the second generic parameter (the owner model).
```php
/** @return HasMany<User, $this> */
public function users(): HasMany
{
    return $this->hasMany(User::class);
}

/** @return BelongsTo<Team, $this> */
public function team(): BelongsTo
{
    return $this->belongsTo(Team::class);
}

/** @return MorphMany<Comment, $this> */
public function comments(): MorphMany
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

## Foreign Key Access

- Avoid directly accessing foreign key columns like `user_id`, `order_id`.
- Use relation-derived methods such as:
  - `$model->relation()->getForeignKeyName()`
  - `$model->relation()->getOwnerKeyName()`
  - `$model->relation()->getMorphType()`
- Prefer relation checks (`->is()`) over manual key comparisons.

The goal is to eliminate hardcoded database assumptions and rely entirely on Laravel's relationship system.

=== .ai/project-context rules ===

# Project Context & Refactoring Guidelines

## What This Project Is

Fleepness is a **quick-commerce multi-vendor marketplace API backend** built with Laravel 12 + PHP 8.4. It was initially developed by a freelancer and is functional but requires systematic improvement across type safety, code quality, admin panel, and e-commerce logic consistency.

The platform combines:
- **Multi-vendor marketplace** — products, orders, seller orders, commissions, fees
- **Live streaming commerce** — LiveKit-powered streams with shoppable products
- **Short video content** — TikTok-style shorts with likes, comments, saves
- **Vendor/seller self-service** — onboarding, product management, withdrawals
- **Custom FCM push notifications** and SMS OTP auth

---

## Primary Refactoring Goals

These goals must guide every code change in this project. Do not introduce new code that contradicts them.

### 1. Strict Type Safety (Highest Priority)

Every PHP file must be strictly typed. This is non-negotiable:

- All method parameters must have type declarations.
- All methods and functions must have explicit return type declarations.
- Use `void` for methods that return nothing, `never` for methods that always throw.
- Replace untyped route parameters (e.g., `$id`) with route model binding wherever possible.
- Replace inline `$request->input()` / `$request->get()` access with typed Form Request classes.
- Use typed DTOs (via `spatie/laravel-data`) for complex input structures.
- Use PHP 8.4 features: property hooks, `readonly` properties, intersection types where appropriate.
- PHPStan level must pass at the configured level (see `phpstan.neon`). Do not suppress errors without justification.

### 2. Pint — Code Style

- After modifying any PHP file, run `vendor/bin/pint --dirty --format agent` to enforce style.
- Do not debate style — Pint is the authority.

### 3. Rector — Automated Refactoring

- Use Rector for automated upgrades to modern PHP/Laravel idioms.
- Run `vendor/bin/rector process` when refactoring legacy controller/model patterns.
- Do not manually rewrite what Rector can handle.

### 4. Form Requests (Not Inline Validation)

- **Every controller method that accepts user input must use a dedicated Form Request.**
- Inline `$request->validate([...])` is not acceptable in any new or refactored code.
- Form Requests must include typed `rules(): array` and `messages(): array` methods.
- Check `/app/Http/Requests/` for existing requests before creating new ones.

### 5. API Resources (Not `->toArray()` or Raw Arrays)

- All API responses must use Eloquent API Resources.
- Resources must use `whenLoaded()` for all relationships to prevent N+1.
- Resources must be typed: `public function toArray(Request $request): array`.
- Check `/app/Http/Resources/` before creating new resource classes.

---

## Admin Panel — Migrate to Filament Entirely

The project has **two admin systems** that must converge into one:

| Current State | Target State |
|---|---|
| Custom Blade admin (`routes/web.php`, prefix `/admin`, 7 custom admin controllers) | Fully replaced by Filament v5 resources |
| Filament v5 installed and configured but **zero resources exist** | All admin operations via Filament |

### Migration Rules

- **Do not add any new functionality to the custom Blade admin.** All new admin features go into Filament resources.
- When refactoring an existing admin controller, create the Filament equivalent first, then remove the legacy controller and route.
- Use `php artisan make:filament-resource` to scaffold resources — always inspect `--help` first.
- Filament resources live in `app/Filament/Resources/`.
- Follow the Filament v5 namespace conventions defined in CLAUDE.md (e.g., `Filament\Actions\`, `Filament\Schemas\Components\`).

### Admin Entities to Migrate (Priority Order)

1. Products (approval, listing, editing)
2. Orders + SellerOrders (status management)
3. Users + Sellers (onboarding approval, role management)
4. Categories + Sections
5. Sliders
6. Payment Methods + Delivery Models
7. Fees & Commissions
8. Settings
9. Dashboard widgets (revenue, order counts, seller stats)

---

## E-Commerce Logic — Standardization Rules

The order and payment flows were written inconsistently. Follow these standards:

### Orders

- An `Order` represents a full customer order (possibly multi-vendor).
- A `SellerOrder` represents the vendor-scoped slice of that order.
- Always create `SellerOrder` records within a `DB::transaction()` when creating an `Order`.
- Order status transitions must go through the `SellerOrder` status enum — never update raw string columns.
- Use `$order->getKey()` / `$sellerOrder->getKey()`, never `$order->id`.

### Pricing & Fees

- Commission, platform fee, VAT, and delivery fee are always stored on the `Order` / `SellerOrder` at creation time — never recalculated after the fact.
- Use the `Fee` model for all platform-defined fee rates — never hardcode percentages.

### Cart

- Cart operations (add, update, delete) must validate product availability and stock before mutating `CartItem`.
- `CartItem::selected` is the authoritative flag for which items flow into an order.

### Morph Maps

- Always use `$model->getMorphClass()` instead of class strings when working with polymorphic relations.
- Register morph maps explicitly in a service provider — do not rely on class name auto-detection.

---

## Architecture Patterns to Follow

- **Services** (`app/Services/`): Stateless, injectable via constructor. Use `#[Singleton]` for expensive services. Never inject `Request` into a service.
- **DTOs** (`spatie/laravel-data`): For any input with 3+ fields crossing a service boundary.
- **Enums**: Use backed enums for all status fields. Keys in TitleCase (e.g., `Pending`, `Accepted`).
- **Events + Listeners**: For side effects (notifications, activity logging) — do not inline them in controllers.
- **Jobs**: For anything slow (SMS, push notifications, payout processing) — always implement `ShouldQueue`.
- **Route Model Binding**: Prefer over manual `Model::findOrFail($id)` in controllers.

---

## What to Avoid

- `DB::` facade — use `Model::query()` instead.
- `env()` outside config files.
- Unguarded models — all models must define `$fillable` or use `$guarded = []` explicitly.
- Magic `$model->id` — use `$model->getKey()`.
- Hardcoded morph class strings — use `$model->getMorphClass()`.
- Inline HTTP clients — use `Http::macro()` for third-party API integrations.
- Empty constructors with no parameters.
- Missing return types on any method.
- Inline validation in controllers.
- Raw `->toArray()` in API responses.

=== .ai/realtime-reverb rules ===

# Real-Time Architecture — Laravel Reverb + FCM

## Problem Statement

The current setup uses the **FCM broadcaster as both the push notification driver and the real-time broadcast driver**. FCM is a push notification delivery system — it has inherent delivery latency and is not a WebSocket connection. Using it for in-app real-time updates (livestream comments, like counts, order status changes) causes lag that is unacceptable for a live commerce experience.

## Target Architecture — Dual Channel Strategy

Split responsibilities clearly:

| Responsibility | Technology | When Used |
|---|---|---|
| **In-app real-time** (user has app open, WebSocket alive) | **Laravel Reverb** | Active users, live sessions |
| **Offline / background push** (app closed or backgrounded) | **FCM** | User not connected to Reverb |

This is not an either/or — both must coexist. A user watching a livestream needs sub-100ms Reverb delivery. The same user closing the app still needs FCM push notifications.

---

## Reverb Setup Requirements

### Installation

```bash
php artisan install:broadcasting
```

This installs `laravel/reverb` and scaffolds the broadcasting config. Always check `php artisan install:broadcasting --help` first.

### Environment Configuration

```dotenv
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

# Keep FCM config intact for push channel

FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
```

### Running Reverb

```bash
php artisan reverb:start
```

In production with Octane, Reverb runs as a separate long-running process. Do not run Reverb inside Octane workers.

---

## What Goes Through Reverb vs FCM

### Reverb (WebSocket — real-time, in-app only)

These events require WebSocket delivery because they update live UI:

| Event | Channel | Why Reverb |
|---|---|---|
| Livestream comments | `livestream_{id}` | Sub-second delivery required |
| Livestream like count changes | `livestream_{id}` | High-frequency counter update |
| Livestream created/updated | `livestream_feed` | Feed must update instantly |
| Order status changes | `user_{id}` | User is on the order screen |
| Seller order accepted/rejected | `user_{id}` | Vendor is on orders screen |
| Cart updates (multi-device sync) | `user_{id}` | User may have multiple devices |
| Seller withdrawal approved | `user_{id}` | In-app status update |

### FCM (Push Notification — offline/background only)

FCM is retained for notifying users who are **not actively connected via WebSocket**:

| Notification | Channel | Why FCM |
|---|---|---|
| New order received (to seller) | `fcm-device` | Seller may not have app open |
| Seller status approved/rejected | `fcm-device` | Background notification |
| OTP login | SMS only — no change | Unrelated to broadcasting |
| Withdrawal approved | `fcm-device` | Background alert |

---

## Broadcasting Channel Definitions

No changes needed to `routes/channels.php` — the existing channel authorization logic is correct. Reverb respects the same channel auth.

```php
// Already correct — no changes needed
Broadcast::channel('user_{id}', function (User $user, $id) {
    return (int) $user->getKey() === (int) $id;
}, ['guards' => ['sanctum']]);

Broadcast::channel('livestream_feed', fn () => true);

Broadcast::channel('livestream_{livestream}', function (?User $user, Livestream $livestream) {
    return LivestreamStatuses::STARTED === $livestream->status;
}, ['guards' => ['sanctum']]);
```

---

## Notification Class Pattern — Dual Channel

For events that need both real-time and push (e.g., order status), the notification must support both:

```php
class OrderStatusChanged extends Notification implements ShouldBroadcast, ShouldQueue
{
    public function via(object $notifiable): array
    {
        // Reverb handles in-app; FCM handles background push
        return ['broadcast', 'fcm-device', 'database'];
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel("user_{$this->order->user->getKey()}")];
    }

    public function broadcastAs(): string
    {
        return 'order_status_changed';
    }

    public function broadcastWith(): array
    {
        return (new OrderResource($this->order))->resolve();
    }

    // FCM push payload for background delivery
    public function toFcm(object $notifiable): CloudMessage
    {
        return CloudMessage::new()
            ->withNotification(Notification::create('Order Update', "Your order status has changed."))
            ->withData(['event' => 'order_status_changed', 'order_id' => (string) $this->order->getKey()]);
    }

    public function toFcmTokens(object $notifiable): array
    {
        return $notifiable->deviceTokens->pluck('token')->all();
    }
}
```

### Key Rules for Dual-Channel Notifications

- Always include `'broadcast'` in `via()` for Reverb delivery.
- Include `'fcm-device'` in `via()` only when a push notification is also needed.
- `broadcastWith()` must return a typed array from an API Resource — never raw model attributes.
- The FCM `toFcm()` payload should be minimal — just enough to open the right screen.
- Use `ShouldQueueAfterCommit` instead of `ShouldQueue` when the notification depends on a DB write completing first.

---

## Presence Channels for Livestreams

Livestream viewer count and participant lists should use a **presence channel**, not a plain channel. This allows clients to know who is watching.

```php
Broadcast::channel('livestream_{livestream}', function (User $user, Livestream $livestream) {
    if (LivestreamStatuses::STARTED !== $livestream->status) {
        return false;
    }
    return [
        'id' => $user->getKey(),
        'name' => $user->name,
    ];
}, ['guards' => ['sanctum']]);
```

Client subscribes to `presence-livestream_{id}`. Reverb automatically tracks member join/leave events.

---

## Existing FCM Broadcaster — Do Not Remove

The custom `FcmBroadcaster` in `app/Support/Broadcaster/FcmBroadcaster.php` must be **retained** as the `fcm` driver for the broadcast manager. It is still used by notifications that go through `via(['broadcast'])` when the broadcast connection is `fcm` — but with Reverb as the default connection, it becomes a secondary driver used only for topic-based push broadcasting if needed.

Register both drivers:

```php
// AppServiceProvider — already exists, keep as-is
Broadcast::resolved(function (BroadcastManager $service): void {
    $service->extend('fcm', fn (Application $app, array $config) => $app->make(FcmBroadcaster::class));
});
```

---

## Deployment Notes

- Reverb must run as a separate supervised process (Supervisor, `php artisan reverb:start`).
- Do not run Reverb behind the same Octane server instance.
- Reverb requires a persistent TCP connection — ensure load balancers are configured for WebSocket (sticky sessions or WebSocket-aware proxy).
- Set `REVERB_HOST` to the public hostname in production, not `127.0.0.1`.
- For horizontal scaling, Reverb supports Redis as a pub/sub backend — configure when deploying multiple Reverb nodes.

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v12
- laravel/octane (OCTANE) - v2
- laravel/prompts (PROMPTS) - v0
- laravel/reverb (REVERB) - v1
- laravel/sanctum (SANCTUM) - v4
- laravel/socialite (SOCIALITE) - v5
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/boost (BOOST) - v2
- laravel/envoy (ENVOY) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `socialite-development` — Manages OAuth social authentication with Laravel Socialite. Activate when adding social login providers; configuring OAuth redirect/callback flows; retrieving authenticated user details; customizing scopes or parameters; setting up community providers; testing with Socialite fakes; or when the user mentions social login, OAuth, Socialite, or third-party authentication.
- `pest-testing` — Use this skill for Pest PHP testing in Laravel projects only. Trigger whenever any test is being written, edited, fixed, or refactored — including fixing tests that broke after a code change, adding assertions, converting PHPUnit to Pest, adding datasets, and TDD workflows. Always activate when the user asks how to write something in Pest, mentions test files or directories (tests/Feature, tests/Unit, tests/Browser), or needs browser testing, smoke testing multiple pages for JS errors, or architecture tests. Covers: it()/expect() syntax, datasets, mocking, browser testing (visit/click/fill), smoke testing, arch(), Livewire component tests, RefreshDatabase, and all Pest 4 features. Do not use for factories, seeders, migrations, controllers, models, or non-test PHP code.
- `medialibrary-development` — Build and work with spatie/laravel-medialibrary features including associating files with Eloquent models, defining media collections and conversions, generating responsive images, and retrieving media URLs and paths.
- `spatie-laravel-php-standards` — Apply Spatie's Laravel and PHP coding standards for any task that creates, edits, reviews, refactors, or formats Laravel/PHP code or Blade templates; use for controllers, Eloquent models, routes, config, validation, migrations, tests, and related files to align with Laravel conventions and PSR-12.
- `eloquent-best-practices` — Best practices for Laravel Eloquent ORM including query optimization, relationship management, and avoiding common pitfalls like N+1 queries.
- `laravel-permission-development` — Build and work with Spatie Laravel Permission features, including roles, permissions, middleware, policies, teams, and Blade directives.
- `laravel-specialist` — Build and configure Laravel 10+ applications, including creating Eloquent models and relationships, implementing Sanctum authentication, configuring Horizon queues, designing RESTful APIs with API resources, and building reactive interfaces with Livewire. Use when creating Laravel models, setting up queue workers, implementing Sanctum auth flows, building Livewire components, optimising Eloquent queries, or writing Pest/PHPUnit tests for Laravel features.
- `php-pro` — Use when building PHP applications with modern PHP 8.3+ features, Laravel, or Symfony frameworks. Invokes strict typing, PHPStan level 9, async patterns with Swoole, and PSR standards. Creates controllers, configures middleware, generates migrations, writes PHPUnit/Pest tests, defines typed DTOs and value objects, sets up dependency injection, and scaffolds REST/GraphQL APIs. Use when working with Eloquent, Doctrine, Composer, Psalm, ReactPHP, or any PHP API development.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan Commands

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`, `php artisan tinker --execute "..."`).
- Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Debugging

- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.
- To execute PHP code for debugging, run `php artisan tinker --execute "your code here"` directly.
- To read configuration values, read the config files directly or run `php artisan config:show [key]`.
- To inspect routes, run `php artisan route:list` directly.
- To check environment variables, read the `.env` file directly.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd and will be available at: `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs for the user.
- You must not run any commands to make the site available via HTTP(S). It is always available through Laravel Herd.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app/Console/Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== octane/core rules ===

# Octane

- Octane boots the application once and reuses it across requests, so singletons persist between requests.
- The Laravel container's `scoped` method may be used as a safe alternative to `singleton`.
- Never inject the container, request, or config repository into a singleton's constructor; use a resolver closure or `bind()` instead:

```php
// Bad
$this->app->singleton(Service::class, fn (Application $app) => new Service($app['request']));

// Good
$this->app->singleton(Service::class, fn () => new Service(fn () => request()));
```

- Never append to static properties, as they accumulate in memory across requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== filament/filament rules ===

## Filament

- Filament is used by this application. Follow the existing conventions for how and where it is implemented.
- Filament is a Server-Driven UI (SDUI) framework for Laravel that lets you define user interfaces in PHP using structured configuration objects. Built on Livewire, Alpine.js, and Tailwind CSS.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices. If `search-docs` is unavailable, refer to https://filamentphp.com/docs.

### Artisan

- Always use Filament-specific Artisan commands to create files. Find available commands with the `list-artisan-commands` tool, or run `php artisan --help`.
- Always inspect required options before running a command, and always pass `--no-interaction`.

### Patterns

Always use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field visibility" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),

</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column value" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),

</code-snippet>

Actions encapsulate a button with an optional modal form and logic:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('updateEmail')
    ->schema([
        TextInput::make('email')
            ->email()
            ->required(),
    ])
    ->action(fn (array $data, User $record) => $record->update($data))

</code-snippet>

### Testing

Always authenticate before testing panel functionality. Filament uses Livewire, so use `Livewire::test()` or `livewire()` (available when `pestphp/pest-plugin-livewire` is in `composer.json`):

<code-snippet name="Table test" lang="php">
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->assertCanSeeTableRecords($users)
    ->searchTable($users->first()->name)
    ->assertCanSeeTableRecords($users->take(1))
    ->assertCanNotSeeTableRecords($users->skip(1));

</code-snippet>

<code-snippet name="Create resource test" lang="php">
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => 'Test',
        'email' => 'test@example.com',
    ])
    ->call('create')
    ->assertNotified()
    ->assertRedirect();

assertDatabaseHas(User::class, [
    'name' => 'Test',
    'email' => 'test@example.com',
]);

</code-snippet>

<code-snippet name="Testing validation" lang="php">
use function Pest\Livewire\livewire;

livewire(CreateUser::class)
    ->fillForm([
        'name' => null,
        'email' => 'invalid-email',
    ])
    ->call('create')
    ->assertHasFormErrors([
        'name' => 'required',
        'email' => 'email',
    ])
    ->assertNotNotified();

</code-snippet>

<code-snippet name="Calling actions in pages" lang="php">
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

livewire(EditUser::class, ['record' => $user->id])
    ->callAction(DeleteAction::class)
    ->assertNotified()
    ->assertRedirect();

</code-snippet>

<code-snippet name="Calling actions in tables" lang="php">
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

livewire(ListUsers::class)
    ->callAction(TestAction::make('promote')->table($user), [
        'role' => 'admin',
    ])
    ->assertNotified();

</code-snippet>

### Correct Namespaces

- Form fields (`TextInput`, `Select`, etc.): `Filament\Forms\Components\`
- Infolist entries (`TextEntry`, `IconEntry`, etc.): `Filament\Infolists\Components\`
- Layout components (`Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`, etc.): `Filament\Schemas\Components\`
- Schema utilities (`Get`, `Set`, etc.): `Filament\Schemas\Components\Utilities\`
- Actions (`DeleteAction`, `CreateAction`, etc.): `Filament\Actions\`. Never use `Filament\Tables\Actions\`, `Filament\Forms\Actions\`, or any other sub-namespace for actions.
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

### Common Mistakes

- **Never assume public file visibility.** File visibility is `private` by default. Always use `->visibility('public')` when public access is needed.
- **Never assume full-width layout.** `Grid`, `Section`, and `Fieldset` do not span all columns by default. Explicitly set column spans when needed.

=== spatie/laravel-medialibrary rules ===

## Media Library

- `spatie/laravel-medialibrary` associates files with Eloquent models, with support for collections, conversions, and responsive images.
- Always activate the `medialibrary-development` skill when working with media uploads, conversions, collections, responsive images, or any code that uses the `HasMedia` interface or `InteractsWithMedia` trait.

=== spatie/boost-spatie-guidelines rules ===

# Project Coding Guidelines

- This codebase follows Spatie's Laravel & PHP guidelines.
- Always activate the `spatie-laravel-php-standards` skill whenever writing, editing, reviewing, or formatting Laravel or PHP code.

</laravel-boost-guidelines>
