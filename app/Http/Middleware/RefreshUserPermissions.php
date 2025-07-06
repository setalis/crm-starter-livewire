<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class RefreshUserPermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Проверяем время последнего изменения разрешений
            $lastPermissionUpdate = Cache::get('permissions_last_update', 0);
            $userCacheKey = 'user_permissions_cache_time_' . $user->id;
            $userLastCheck = Cache::get($userCacheKey, 0);
            
            // Если разрешения обновлялись после последней проверки пользователя
            if ($lastPermissionUpdate > $userLastCheck) {
                // Очищаем кеш разрешений пользователя
                $user->forgetCachedPermissions();
                
                // Обновляем время последней проверки для этого пользователя
                Cache::put($userCacheKey, now()->timestamp, 3600);
            }
        }

        return $next($request);
    }
} 