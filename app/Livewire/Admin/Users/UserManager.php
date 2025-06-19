<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingUser = null;
    
    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRoles = [];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'nullable|min:6|confirmed',
        'selectedRoles' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Имя обязательно для заполнения.',
        'email.required' => 'Email обязателен для заполнения.',
        'email.email' => 'Введите корректный email адрес.',
        'password.min' => 'Пароль должен содержать минимум 6 символов.',
        'password.confirmed' => 'Пароли не совпадают.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editingUser = null;
        $this->showModal = true;
    }

    public function edit(User $user)
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function save()
    {
        // Валидация
        $rules = $this->rules;
        
        if ($this->editingUser) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->editingUser->id;
            if (empty($this->password)) {
                unset($rules['password']);
            }
        } else {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        // Проверка прав доступа
        if (!auth()->user()->can('users.create') && !$this->editingUser) {
            session()->flash('error', 'У вас нет прав для создания пользователей.');
            return;
        }

        if (!auth()->user()->can('users.edit') && $this->editingUser) {
            session()->flash('error', 'У вас нет прав для редактирования пользователей.');
            return;
        }

        try {
            if ($this->editingUser) {
                // Обновление существующего пользователя
                $this->editingUser->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password ? Hash::make($this->password) : $this->editingUser->password,
                ]);
                $user = $this->editingUser;
            } else {
                // Создание нового пользователя
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
            }

            // Синхронизация ролей
            $user->syncRoles($this->selectedRoles);

            $this->resetForm();
            $this->showModal = false;
            
            session()->flash('success', $this->editingUser ? 'Пользователь успешно обновлен.' : 'Пользователь успешно создан.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка: ' . $e->getMessage());
        }
    }

    public function delete(User $user)
    {
        if (!auth()->user()->can('users.delete')) {
            session()->flash('error', 'У вас нет прав для удаления пользователей.');
            return;
        }

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Вы не можете удалить себя.');
            return;
        }

        try {
            $user->delete();
            session()->flash('success', 'Пользователь успешно удален.');
        } catch (\Exception $e) {
            session()->flash('error', 'Произошла ошибка при удалении пользователя.');
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = [];
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with('roles')
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.admin.users.user-manager', compact('users', 'roles'));
    }
}
