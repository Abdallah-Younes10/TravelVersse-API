<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $user = auth()->user();

         if (! $user || ! in_array($user->user_type, $types)) {
            return response()->json(['message' => 'Unauthorized' , $user], 403);
        }

        return $next($request);
    }
}
