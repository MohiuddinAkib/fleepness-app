# Laravel Native Primitives

Prefer Laravel's built-in model and HTTP primitives over direct property/column access or manual client instantiation. The goal is to use as much of Laravel's native API as possible.

## Eloquent Model Primitives

- Use `$model->getKey()` instead of `$model->id` when referencing a model's primary key.
- Use `$model->getMorphClass()` instead of hardcoding `modelable_type` or referencing the morph class string directly.

## HTTP Client

- Use `Http::macro()` to integrate third-party API services rather than instantiating custom HTTP clients manually.
