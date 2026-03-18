<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SessionController extends Controller
{
    public function destroy(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return Response::json(['message' => 'Logged out successfully.']);
    }
}
