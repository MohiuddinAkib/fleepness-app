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
