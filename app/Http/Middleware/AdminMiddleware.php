<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Проверка, аутентифицирован ли пользователь и имеет ли он роль 'admin'
        if (Auth::check() && Auth::user()->hasRole('ADMIN')) {
            return $next($request);
        }

        // Если пользователь не администратор, перенаправляем на главную страницу
        return redirect('/')->with('error', 'Access denied. You do not have administrative privileges.');
    }
}
