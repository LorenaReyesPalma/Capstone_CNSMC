<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class AccountLockMiddleware
{
    public function handle($request, Closure $next)
    {
        $email = $request->email;
        $key = 'login_attempts_' . $email;

        if (Cache::has($key) && Cache::get($key) >= 3) {
            $timeLeft = Cache::get('lock_time_' . $email) - now()->timestamp;
            if ($timeLeft > 0) {
                return back()->withErrors(['email' => 'Cuenta bloqueada. Intente nuevamente en ' . gmdate('i:s', $timeLeft) . ' minutos.']);
            } else {
                Cache::forget($key);
                Cache::forget('lock_time_' . $email);
            }
        }

        return $next($request);
    }
}
