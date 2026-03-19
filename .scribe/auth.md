# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_BEARER_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Obtain a token by registering via <code>POST /api/auth/register</code> and verifying your OTP via <code>POST /api/auth/verify-otp</code>. Pass the token as <code>Authorization: Bearer {token}</code>.
