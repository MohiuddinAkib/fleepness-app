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
