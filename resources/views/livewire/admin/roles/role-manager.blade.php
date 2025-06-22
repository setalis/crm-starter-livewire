<div>
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Управление ролями</h1>
    
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 dark:bg-green-950 dark:border-green-800 dark:text-green-200">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 dark:bg-red-950 dark:border-red-800 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div class="flex-1 max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Поиск ролей..." 
                data-flux-control
                data-flux-group-target
                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
        </div>
        
        @can('roles.create')
            <button wire:click="create" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Добавить роль
            </button>
        @endcan
    </div>

    <!-- Адаптивная таблица ролей -->
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <!-- Десктопная версия -->
        <div class="hidden md:block">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Роль</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Пользователи</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Разрешения</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Создана</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $role->users_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $role->permissions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ \App\Helpers\Settings::formatDateTime($role->created_at) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @can('roles.edit')
                                        <button wire:click="edit({{ $role->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            Редактировать
                                        </button>
                                    @endcan
                                    @can('roles.delete')
                                        <button 
                                            wire:click="delete({{ $role->id }})" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 {{ ($role->users_count > 0 && !auth()->user()->isSuperAdmin()) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            wire:confirm="Вы уверены, что хотите удалить эту роль?"
                                            {{ ($role->users_count > 0 && !auth()->user()->isSuperAdmin()) ? 'disabled' : '' }}
                                        >
                                            Удалить
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                Роли не найдены
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Мобильная версия (карточки) -->
        <div class="md:hidden bg-white dark:bg-gray-900">
            @forelse ($roles as $role)
                <div class="border-b border-gray-200 dark:border-gray-700 p-4 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <!-- Заголовок карточки -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</h3>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Создана: {{ \App\Helpers\Settings::formatDate($role->created_at) }}
                            </div>
                        </div>
                    </div>

                    <!-- Статистика -->
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Пользователи</div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $role->users_count }}
                            </span>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Разрешения</div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                {{ $role->permissions_count }}
                            </span>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="flex items-center justify-end space-x-3 pt-2 border-t border-gray-100 dark:border-gray-600">
                        @can('roles.edit')
                            <button wire:click="edit({{ $role->id }})" class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Редактировать
                            </button>
                        @endcan
                        @can('roles.delete')
                            <button 
                                wire:click="delete({{ $role->id }})" 
                                class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 {{ ($role->users_count > 0 && !auth()->user()->isSuperAdmin()) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                wire:confirm="Вы уверены, что хотите удалить эту роль?"
                                {{ ($role->users_count > 0 && !auth()->user()->isSuperAdmin()) ? 'disabled' : '' }}
                            >
                                Удалить
                            </button>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    Роли не найдены
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $roles->links() }}
    </div>


</div>
