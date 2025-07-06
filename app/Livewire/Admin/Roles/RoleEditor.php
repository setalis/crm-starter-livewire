<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Permission;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RoleEditor extends Component
{
    public $role;
    public $isEditing = false;
    
    // Form fields
    public $name = '';
    public $selectedPermissions = [];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedPermissions' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Название роли обязательно для заполнения.',
    ];

    public function mount($roleId = null)
    {
        if ($roleId) {
            $this->role = Role::findOrFail($roleId);
            $this->isEditing = true;
            $this->name = $this->role->name;
            $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        // Валидация
        $rules = $this->rules;
        
        if ($this->isEditing) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->role->id;
        } else {
            $rules['name'] = 'required|string|max:255|unique:roles,name';
        }

        $this->validate($rules);

        // Проверка прав доступа
        if (!auth()->user()->can('roles.create') && !$this->isEditing) {
            session()->flash('error', 'У вас нет прав для создания ролей.');
            return;
        }

        if (!auth()->user()->can('roles.edit') && $this->isEditing) {
            session()->flash('error', 'У вас нет прав для редактирования ролей.');
            return;
        }

        try {
            if ($this->isEditing) {
                // Обновление существующей роли
                $this->role->update([
                    'name' => $this->name,
                ]);
                $role = $this->role;
                
                // Логируем старые разрешения
                $oldPermissions = $role->permissions->pluck('name')->toArray();
                Log::info('Обновление роли', [
                    'role' => $role->name,
                    'old_permissions' => $oldPermissions,
                    'new_permissions' => $this->selectedPermissions
                ]);
            } else {
                // Создание новой роли
                $role = Role::create([
                    'name' => $this->name,
                    'guard_name' => 'web',
                ]);
                $this->role = $role;
                $this->isEditing = true;
                
                Log::info('Создание новой роли', [
                    'role' => $role->name,
                    'permissions' => $this->selectedPermissions
                ]);
            }

            // Синхронизация разрешений
            $result = $role->syncPermissions($this->selectedPermissions);
            
            // Принудительная очистка кеша разрешений
            \Artisan::call('permission:cache-reset');
            Cache::forget('spatie.permission.cache');
            
            // Отмечаем время обновления разрешений для middleware
            Cache::put('permissions_last_update', now()->timestamp, 3600);
            
            // Очищаем кеш всех пользователей с этой ролью
            $usersWithRole = \App\Models\User::role($role->name)->get();
            foreach ($usersWithRole as $user) {
                $user->forgetCachedPermissions();
                Cache::forget('user_permissions_cache_time_' . $user->id);
            }
            
            // Логируем результат
            Log::info('Синхронизация разрешений завершена', [
                'role' => $role->name,
                'permissions_count' => count($this->selectedPermissions),
                'actual_permissions' => $role->fresh()->permissions->pluck('name')->toArray(),
                'affected_users' => $usersWithRole->count()
            ]);
            
            session()->flash('success', $this->isEditing ? 'Роль успешно обновлена. Пользователи с этой ролью получат обновленные разрешения при следующем запросе.' : 'Роль успешно создана.');
            
            // Принудительно обновляем компонент
            $this->dispatch('role-permissions-updated', $role->name);
        } catch (\Exception $e) {
            Log::error('Ошибка при сохранении роли', [
                'error' => $e->getMessage(),
                'role' => $this->name,
                'permissions' => $this->selectedPermissions
            ]);
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function togglePermission($permission)
    {
        if (in_array($permission, $this->selectedPermissions)) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permission]);
        } else {
            $this->selectedPermissions[] = $permission;
        }
    }

    public function toggleSection($sectionPermissionNames)
    {
        // $sectionPermissionNames уже приходит как массив имен разрешений
        $allSelected = count(array_intersect($sectionPermissionNames, $this->selectedPermissions)) === count($sectionPermissionNames);
        
        if ($allSelected) {
            // Снять выделение со всех разрешений секции
            $this->selectedPermissions = array_diff($this->selectedPermissions, $sectionPermissionNames);
        } else {
            // Выделить все разрешения секции
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $sectionPermissionNames));
        }
    }

    public function render()
    {
        $permissions = Permission::with('section')->get()->groupBy('section.display_name');

        return view('livewire.admin.roles.role-editor', compact('permissions'))
            ->layout('components.layouts.app');
    }
} 