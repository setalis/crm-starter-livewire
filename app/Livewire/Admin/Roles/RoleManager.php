<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleManager extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        return redirect()->route('admin.roles.create');
    }

    public function edit(Role $role)
    {
        return redirect()->route('admin.roles.edit', ['roleId' => $role->id]);
    }



    public function delete(Role $role)
    {
        if (!auth()->user()->can('roles.delete')) {
            session()->flash('error', 'У вас нет прав для удаления ролей.');
            return;
        }

        // Проверяем, есть ли пользователи с этой ролью (супер админ может удалять в любом случае)
        if ($role->users()->count() > 0 && !auth()->user()->isSuperAdmin()) {
            session()->flash('error', 'Нельзя удалить роль, которая назначена пользователям.');
            return;
        }

        try {
            $role->delete();
            session()->flash('success', 'Роль успешно удалена.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при удалении роли.');
        }
    }



    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('users', 'permissions')
            ->paginate(10);

        return view('livewire.admin.roles.role-manager', compact('roles'));
    }
}
