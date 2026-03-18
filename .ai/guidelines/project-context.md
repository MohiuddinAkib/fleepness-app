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
