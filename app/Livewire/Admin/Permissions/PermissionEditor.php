<?php

namespace App\Livewire\Admin\Permissions;

use App\Models\Permission;
use App\Models\Section;
use Livewire\Component;

class PermissionEditor extends Component
{
    public $permission;
    public $isEditing = false;
    
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

    public function mount($permissionId = null)
    {
        if ($permissionId) {
            $this->permission = Permission::findOrFail($permissionId);
            $this->isEditing = true;
            $this->name = $this->permission->name;
            $this->display_name = $this->permission->display_name;
            $this->description = $this->permission->description;
            $this->section_id = $this->permission->section_id;
        }
    }

    public function save()
    {
        // Валидация
        $rules = $this->rules;
        
        if ($this->isEditing) {
            $rules['name'] = 'required|string|max:255|unique:permissions,name,' . $this->permission->id;
        } else {
            $rules['name'] = 'required|string|max:255|unique:permissions,name';
        }

        $this->validate($rules);

        // Проверка прав доступа
        if (!auth()->user()->can('permissions.create') && !$this->isEditing) {
            session()->flash('error', 'У вас нет прав для создания разрешений.');
            return;
        }

        if (!auth()->user()->can('permissions.edit') && $this->isEditing) {
            session()->flash('error', 'У вас нет прав для редактирования разрешений.');
            return;
        }

        try {
            if ($this->isEditing) {
                // Обновление существующего разрешения
                $this->permission->update([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'section_id' => $this->section_id ?: null,
                ]);
            } else {
                // Создание нового разрешения
                $this->permission = Permission::create([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'section_id' => $this->section_id ?: null,
                    'guard_name' => 'web',
                ]);
                $this->isEditing = true;
            }
            
            session()->flash('success', $this->isEditing ? 'Разрешение успешно обновлено.' : 'Разрешение успешно создано.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $sections = Section::active()->ordered()->get();

        return view('livewire.admin.permissions.permission-editor', compact('sections'))
            ->layout('components.layouts.app');
    }
} 