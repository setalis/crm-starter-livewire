<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Если пользователь менеджер, перенаправляем его на панель менеджера
        if ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        }
        
        // Если у пользователя есть доступ к админской панели, показываем админский dashboard
        if ($user->hasAdminAccess()) {
            return view('dashboard');
        }
        
        // Если нет доступа ни к одной панели
        abort(403, 'У вас нет доступа к панели управления.');
    }
}
