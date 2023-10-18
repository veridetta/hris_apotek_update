<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $role)
{
    $user = $request->user();

    if (!$user) {
        // Jika pengguna tidak terautentikasi, arahkan mereka ke halaman login
        return redirect('/login');
    }

    if ($user->role === $role) {
        // Jika peran pengguna sesuai dengan peran yang diberikan, lanjutkan
        return $next($request);
    }

    // Jika peran tidak sesuai, Anda dapat mengarahkan pengguna ke halaman yang sesuai atau memberikan pesan kesalahan.
    // Contoh mengarahkan pengguna ke halaman beranda
    return redirect('/');
}

}
