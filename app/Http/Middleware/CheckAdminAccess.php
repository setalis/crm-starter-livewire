<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Проверяем, есть ли у пользователя доступ к админской панели
        if (!$user->hasAdminAccess()) {
            // Если пользователь менеджер, перенаправляем его на панель менеджера
            if ($user->hasRole('manager')) {
                return redirect()->route('manager.dashboard');
            }
            
            abort(403, 'У вас нет доступа к админской панели.');
        }

        return $next($request);
    }
}
