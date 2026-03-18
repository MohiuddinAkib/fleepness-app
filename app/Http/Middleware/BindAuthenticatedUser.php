<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class BindAuthenticatedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->scoped(User::class, fn (): ?User => $request->user());

        return $next($request);
    }
}
