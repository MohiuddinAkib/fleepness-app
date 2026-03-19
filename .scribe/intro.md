# Introduction

Fleepness is a quick-commerce multi-vendor marketplace API. It powers a mobile shopping experience combining product listings, live-stream commerce, short videos, vendor onboarding, order management, and real-time notifications.

<aside>
    <strong>Base URL</strong>: <code>https://fleepness-app.test</code>
</aside>

    Welcome to the **Fleepness API** — a quick-commerce multi-vendor marketplace platform.

    ## Getting Started

    All API endpoints are prefixed with `/api`. The API uses **Laravel Sanctum** bearer token authentication for protected endpoints.

    ### Authentication Flow
    1. Register with your phone number via `POST /api/auth/register`
    2. Verify the OTP received via SMS: `POST /api/auth/verify-otp` to receive a bearer token
    3. Include the token in all authenticated requests: `Authorization: Bearer {token}`

    ### Using The Postman Collection
    1. Download the collection from `/docs.postman`
    2. Set the `baseUrl` collection variable to your target environment, for example `https://fleepness-app.test`
    3. Run the OTP registration and verification requests first
    4. Copy the returned token into the `bearerToken` collection variable
    5. Reuse IDs returned from create and list endpoints when testing reviews, cart operations, orders, and content interactions

    ### Using The OpenAPI Specification
    - Download the machine-readable spec from `/docs.openapi`
    - Import it into Swagger UI, Postman, Insomnia, Stoplight, or any OpenAPI-compatible client
    - Use it as the source of truth for request payloads, authentication requirements, and example responses

    ### Environment Notes
    - Public browsing endpoints can be called without authentication
    - Protected `/api/me/*`, `/api/cart/*`, `/api/orders`, and engagement endpoints require Sanctum bearer authentication
    - Social login endpoints redirect to third-party providers and are best exercised in a browser or mobile deep-link flow

    ### Legacy Compatibility Routes
    Some historical mobile-client routes are still published under the main API surface while the React Native app migrates.

    - Legacy endpoints are documented with explicit replacement paths in their endpoint descriptions
    - Runtime responses from those aliases include `X-Fleepness-Legacy-Endpoint`, `X-Fleepness-Migration-Key`, and `X-Fleepness-Replacement-Endpoints` headers
    - New integrations should always prefer the modern me-scoped or resource-scoped replacement paths over the legacy aliases

    ### Response Format
    Most successful responses follow one of these shapes:
    ```json
    {
      "message": "Human-readable status",
      "data": { ... }
    }
    ```

    ```json
    {
      "data": [ ... ],
      "meta": { ... },
      "links": { ... }
    }
    ```

    ### Error Responses
    | Status | Meaning |
    |--------|---------|
    | 401 | Unauthenticated — missing or invalid token |
    | 403 | Forbidden — authenticated but not authorized |
    | 404 | Resource not found |
    | 422 | Validation error — check `errors` for field-level details |

    <aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right.</aside>

