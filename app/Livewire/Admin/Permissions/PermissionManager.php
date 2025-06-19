<?php

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingPermission = null;
    
    // Form fields
    public $name = '';
    public $display_name = '';
    public $description = '';
    public $section_id = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'display_name' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'section_id' => 'nullable|exists:sections,id',
    ];

    protected $messages = [
        'name.required' => 'Название разрешения обязательно для заполнения.',
        'section_id.exists' => 'Выбранный раздел не существует.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editingPermission = null;
        $this->showModal = true;
    }

    public function edit(Permission $permission)
    {
        $this->editingPermission = $permission;
        $this->name = $permission->name;
        $this->display_name = $permission->display_name;
        $this->description = $permission->description;
        $this->section_id = $permission->section_id;
        $this->showModal = true;
    }

    public function save()
    {
        // Валидация
        $rules = $this->rules;
        
        if ($this->editingPermission) {
            $rules['name'] = 'required|string|max:255|unique:permissions,name,' . $this->editingPermission->id;
        } else {
            $rules['name'] = 'required|string|max:255|unique:permissions,name';
        }

        $this->validate($rules);

        // Проверка прав доступа
        if (!auth()->user()->can('permissions.create') && !$this->editingPermission) {
            session()->flash('error', 'У вас нет прав для создания разрешений.');
            return;
        }

        if (!auth()->user()->can('permissions.edit') && $this->editingPermission) {
            session()->flash('error', 'У вас нет прав для редактирования разрешений.');
            return;
        }

        try {
            if ($this->editingPermission) {
                // Обновление существующего разрешения
                $this->editingPermission->update([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'section_id' => $this->section_id ?: null,
                ]);
            } else {
                // Создание нового разрешения
                Permission::create([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'section_id' => $this->section_id ?: null,
                    'guard_name' => 'web',
                ]);
            }

            $this->resetForm();
            $this->showModal = false;
            
            session()->flash('success', $this->editingPermission ? 'Разрешение успешно обновлено.' : 'Разрешение успешно создано.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function delete(Permission $permission)
    {
        if (!auth()->user()->can('permissions.delete')) {
            session()->flash('error', 'У вас нет прав для удаления разрешений.');
            return;
        }

        // Проверяем, есть ли роли с этим разрешением
        if ($permission->roles()->count() > 0) {
            session()->flash('error', 'Нельзя удалить разрешение, которое назначено ролям.');
            return;
        }

        try {
            $permission->delete();
            session()->flash('success', 'Разрешение успешно удалено.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при удалении разрешения.');
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->display_name = '';
        $this->description = '';
        $this->section_id = '';
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $permissions = Permission::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('display_name', 'like', '%' . $this->search . '%');
            })
            ->with(['section', 'roles'])
            ->withCount('roles')
            ->paginate(10);

        $sections = Section::active()->ordered()->get();

        return view('livewire.admin.permissions.permission-manager', compact('permissions', 'sections'));
    }
}
