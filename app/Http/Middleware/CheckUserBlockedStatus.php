<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBlockedStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_blocked) {

                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Nukreipiame į prisijungimo puslapį su klaidos pranešimu
                // Galite nukreipti į atskirą "blokuoto" puslapį, jei norite
                return redirect()->route('login')->withErrors([
                    'email' => 'Jūsų paskyra yra blokuota. Susisiekite su administratoriumi.',
                ]);
            }
        }

        return $next($request);
    }
}
