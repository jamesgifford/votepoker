<?php

namespace App\Http\Middleware;

use App\Database\Models\User;
use Auth;
use Closure;
use Ramsey\Uuid\Uuid;

class AuthenticateWithTemporaryAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            // Create a temporary user
            $user = new User;
            $user->is_temporary = true;
            $user->name         = Uuid::uuid4();
            $user->email        = Uuid::uuid4();
            $user->password     = Uuid::uuid4();
            $user->save();

            // Authenticate the temporary user
            Auth::login($user, true);
        }

        return $next($request);
    }
}
