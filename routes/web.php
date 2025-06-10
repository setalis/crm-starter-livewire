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
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('units', UnitManager::class)->name('units.index');
    Route::get('elements', ElementManager::class)->name('elements.index');
    Route::get('products', ProductManager::class)->name('products.index');
    Route::get('operations', OperationManager::class)->name('operations.index');
    Route::get('operations/{type}', OperationManager::class)
        ->whereIn('type', ['purchase', 'sale'])
        ->name('operations.create');
    Route::get('cash-register', CashRegisterManager::class)->name('cash-register.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
