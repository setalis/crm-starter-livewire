<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckManagerAccess
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
        
        // Проверяем, что пользователь имеет роль менеджера
        if (!$user->hasRole('manager')) {
            // Если у пользователя есть доступ к админской панели, перенаправляем туда
            if ($user->hasAdminAccess()) {
                return redirect()->route('dashboard');
            }
            
            abort(403, 'У вас нет доступа к панели менеджера.');
        }

        return $next($request);
    }
}
