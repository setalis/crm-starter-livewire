<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Permission;
use Livewire\Component;
use Spatie\Permission\Models\Role;

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
            } else {
                // Создание новой роли
                $role = Role::create([
                    'name' => $this->name,
                    'guard_name' => 'web',
                ]);
                $this->role = $role;
                $this->isEditing = true;
            }

            // Синхронизация разрешений
            $role->syncPermissions($this->selectedPermissions);
            
            session()->flash('success', $this->isEditing ? 'Роль успешно обновлена.' : 'Роль успешно создана.');
        } catch (\Exception $e) {
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

    public function toggleSection($sectionPermissions)
    {
        $sectionPermissionNames = $sectionPermissions->pluck('name')->toArray();
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