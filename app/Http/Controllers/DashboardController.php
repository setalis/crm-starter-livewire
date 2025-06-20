<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Определяем тип панели для пользователя
        $panelType = $user->getPanelType();
        
        switch ($panelType) {
            case 'manager':
                return redirect()->route('manager.dashboard');
            
            case 'admin':
                if ($user->hasAdminAccess()) {
                    return view('dashboard');
                }
                break;
        }
        
        // Если нет доступа ни к одной панели
        abort(403, 'У вас нет доступа к панели управления.');
    }
}
