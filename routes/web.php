<?php

use App\Livewire\Admin\Units\UnitManager;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\Units\Index as Units;
use App\Livewire\Admin\ElementManager;
use App\Livewire\Admin\Products\ProductManager;
use App\Livewire\Admin\Operations\OperationManager;
use App\Livewire\Admin\CashRegisterManager;
use App\Livewire\Admin\Warehouse\StockManager;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Admin\Shipments\ShipmentManager;
use App\Livewire\Admin\Conversions\ConversionManager;
use App\Livewire\Admin\Users\UserManager;
use App\Livewire\Admin\Roles\RoleManager;
use App\Livewire\Admin\Permissions\PermissionManager;
use App\Livewire\Admin\Sections\SectionManager;
use App\Livewire\Admin\Comments\CommentManager;
use App\Livewire\Admin\Recounts\RecountManager;
use App\Livewire\Admin\Recounts\CashRecountManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Админская панель (для супер админов, админов и бухгалтеров)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Управление пользователями и правами доступа
    Route::middleware('can:users.view')->get('users', UserManager::class)->name('users.index');
    Route::middleware('can:roles.view')->get('roles', RoleManager::class)->name('roles.index');
    Route::middleware('can:roles.create')->get('roles/create', \App\Livewire\Admin\Roles\RoleEditor::class)->name('roles.create');
    Route::middleware('can:roles.edit')->get('roles/{roleId}/edit', \App\Livewire\Admin\Roles\RoleEditor::class)->name('roles.edit');
    Route::middleware('can:permissions.view')->get('permissions', PermissionManager::class)->name('permissions.index');
    Route::middleware('can:sections.view')->get('sections', SectionManager::class)->name('sections.index');
    
    // Основные разделы
    Route::get('units', UnitManager::class)->name('units.index');
    Route::get('elements', ElementManager::class)->name('elements.index');
    Route::get('products', ProductManager::class)->name('products.index');
    Route::get('operations', OperationManager::class)->name('operations.index');
    Route::get('operations/{type}', OperationManager::class)
        ->whereIn('type', ['purchase', 'sale'])
        ->name('operations.create');
    Route::get('cash-register', CashRegisterManager::class)->name('cash-register.index');
    Route::get('warehouse/stock', StockManager::class)->name('warehouse.stock.index');
    Route::get('shipments', ShipmentManager::class)->name('shipments.index');
    Route::get('conversions', ConversionManager::class)->name('conversions.index');
    Route::middleware('can:recounts.view')->get('recounts', RecountManager::class)->name('recounts.index');
    Route::middleware('can:cash_recounts.view')->get('cash-recounts', CashRecountManager::class)->name('cash-recounts.index');
    Route::middleware('can:comments.view')->get('comments', CommentManager::class)->name('comments.index');
    Route::get('reports', \App\Livewire\Admin\Reports\ReportManager::class)->name('reports.index');
});

// Панель менеджера (с проверкой разрешений)
Route::middleware(['auth', 'verified', 'manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::view('dashboard', 'manager.dashboard')->name('dashboard');
    
    // Справочники
    Route::middleware('can:units.view')->get('units', UnitManager::class)->name('units.index');
    Route::middleware('can:elements.view')->get('elements', ElementManager::class)->name('elements.index');
    Route::middleware('can:products.view')->get('products', ProductManager::class)->name('products.index');
    
    // Операции
    Route::middleware('can:operations.view')->get('operations', OperationManager::class)->name('operations.index');
    Route::middleware('can:operations.create')->get('operations/{type}', OperationManager::class)
        ->whereIn('type', ['purchase', 'sale'])
        ->name('operations.create');
    
    // Склад
    Route::middleware('can:warehouse.view')->get('warehouse/stock', StockManager::class)->name('warehouse.stock.index');
    Route::middleware('can:shipments.view')->get('shipments', ShipmentManager::class)->name('shipments.index');
    Route::middleware('can:conversions.view')->get('conversions', ConversionManager::class)->name('conversions.index');
    Route::middleware('can:recounts.view')->get('recounts', RecountManager::class)->name('recounts.index');
    
    // Финансы
    Route::middleware('can:cash.view')->get('cash-register', CashRegisterManager::class)->name('cash-register.index');
    Route::middleware('can:cash_recounts.view')->get('cash-recounts', CashRecountManager::class)->name('cash-recounts.index');
    
    // Отчеты
    Route::middleware('can:analytics.view')->get('reports', \App\Livewire\Admin\Reports\ReportManager::class)->name('reports.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::middleware('can:settings.manage')->get('settings/application', \App\Livewire\Settings\ApplicationSettings::class)->name('settings.application');
});

require __DIR__.'/auth.php';
