<?php

namespace App\Livewire\Admin\Sections;

use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;

class SectionManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingSection = null;
    
    // Form fields
    public $name = '';
    public $display_name = '';
    public $description = '';
    public $icon = '';
    public $sort_order = 0;
    public $is_active = true;
    
    protected $rules = [
        'name' => 'required|string|max:255|regex:/^[a-z_]+$/',
        'display_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'icon' => 'nullable|string|max:255',
        'sort_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Имя раздела обязательно для заполнения.',
        'name.regex' => 'Имя раздела может содержать только строчные буквы и подчеркивания.',
        'display_name.required' => 'Отображаемое имя обязательно для заполнения.',
        'sort_order.required' => 'Порядок сортировки обязателен.',
        'sort_order.integer' => 'Порядок сортировки должен быть числом.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editingSection = null;
        $this->showModal = true;
    }

    public function edit(Section $section)
    {
        $this->editingSection = $section;
        $this->name = $section->name;
        $this->display_name = $section->display_name;
        $this->description = $section->description;
        $this->icon = $section->icon;
        $this->sort_order = $section->sort_order;
        $this->is_active = $section->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        // Валидация
        $rules = $this->rules;
        
        if ($this->editingSection) {
            $rules['name'] = 'required|string|max:255|regex:/^[a-z_]+$/|unique:sections,name,' . $this->editingSection->id;
        } else {
            $rules['name'] = 'required|string|max:255|regex:/^[a-z_]+$/|unique:sections,name';
        }

        $this->validate($rules);

        // Проверка прав доступа
        if (!auth()->user()->can('sections.create') && !$this->editingSection) {
            session()->flash('error', 'У вас нет прав для создания разделов.');
            return;
        }

        if (!auth()->user()->can('sections.edit') && $this->editingSection) {
            session()->flash('error', 'У вас нет прав для редактирования разделов.');
            return;
        }

        try {
            if ($this->editingSection) {
                // Обновление существующего раздела
                $this->editingSection->update([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'icon' => $this->icon,
                    'sort_order' => $this->sort_order,
                    'is_active' => $this->is_active,
                ]);
            } else {
                // Создание нового раздела
                Section::create([
                    'name' => $this->name,
                    'display_name' => $this->display_name,
                    'description' => $this->description,
                    'icon' => $this->icon,
                    'sort_order' => $this->sort_order,
                    'is_active' => $this->is_active,
                ]);
            }

            $this->resetForm();
            $this->showModal = false;
            
            session()->flash('success', $this->editingSection ? 'Раздел успешно обновлен.' : 'Раздел успешно создан.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function delete(Section $section)
    {
        if (!auth()->user()->can('sections.delete')) {
            session()->flash('error', 'У вас нет прав для удаления разделов.');
            return;
        }

        // Проверяем, есть ли связанные разрешения
        if ($section->permissions()->count() > 0) {
            session()->flash('error', 'Нельзя удалить раздел, к которому привязаны разрешения.');
            return;
        }

        try {
            $section->delete();
            session()->flash('success', 'Раздел успешно удален.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при удалении раздела.');
        }
    }

    public function toggleStatus(Section $section)
    {
        if (!auth()->user()->can('sections.edit')) {
            session()->flash('error', 'У вас нет прав для изменения статуса разделов.');
            return;
        }

        try {
            $section->update(['is_active' => !$section->is_active]);
            session()->flash('success', 'Статус раздела изменен.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при изменении статуса.');
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->display_name = '';
        $this->description = '';
        $this->icon = '';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $sections = Section::query()
            ->when($this->search, function ($query) {
                $query->where('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('permissions')
            ->ordered()
            ->paginate(10);

        return view('livewire.admin.sections.section-manager', compact('sections'));
    }
}
