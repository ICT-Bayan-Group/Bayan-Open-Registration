<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminActionPassword
{
    // Hash dari "Okedeh.12345!" — generate sekali dengan bcrypt
    // php artisan tinker → bcrypt('Okedeh.12345!')
    private const PASSWORD_HASH = '$2y$12$YOUR_BCRYPT_HASH_HERE';

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Admin-Action-Token');

        if (! $token || ! \Hash::check($token, self::PASSWORD_HASH)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}