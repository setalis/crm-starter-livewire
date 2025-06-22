<div>
    <flux:heading size="xl">Управление разделами</flux:heading>
    
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Поиск разделов..." 
                data-flux-control
                data-flux-group-target
                class="w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5">
        </div>
        
        @can('sections.create')
            <button wire:click="create" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Добавить раздел
            </button>
        @endcan
    </div>

    <!-- Адаптивная таблица разделов -->
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <!-- Десктопная версия таблицы -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Раздел</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Описание</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Разрешения</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Порядок</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($sections as $section)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    @if($section->icon)
                                        <div class="h-5 w-5 text-gray-400">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $section->display_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $section->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded text-gray-800">{{ $section->name }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="max-w-xs truncate text-gray-900" title="{{ $section->description }}">
                                    {{ $section->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $section->permissions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $section->sort_order }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @can('sections.edit')
                                    <button wire:click="toggleStatus({{ $section->id }})" 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                    </span>
                                @endcan
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @can('sections.edit')
                                        <button wire:click="edit({{ $section->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors duration-150" title="Редактировать">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    @endcan
                                    @can('sections.delete')
                                        <button 
                                            wire:click="delete({{ $section->id }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 bg-red-100 hover:bg-red-200 {{ $section->permissions_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            wire:confirm="Вы уверены, что хотите удалить этот раздел?"
                                            {{ $section->permissions_count > 0 ? 'disabled' : '' }}
                                            title="Удалить"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Разделы не найдены</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Планшетная версия таблицы -->
        <div class="hidden md:block lg:hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <div class="grid grid-cols-4 gap-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div>Раздел</div>
                    <div>Разрешения</div>
                    <div>Статус</div>
                    <div class="text-center">Действия</div>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($sections as $section)
                <div class="px-4 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="grid grid-cols-4 gap-4 items-center">
                        <div>
                            <div class="flex items-center space-x-2">
                                @if($section->icon)
                                    <div class="h-4 w-4 text-gray-400">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $section->display_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $section->name }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="{{ $section->description }}">
                                {{ $section->description }}
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $section->permissions_count }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">Порядок: {{ $section->sort_order }}</div>
                        </div>
                        <div>
                            @can('sections.edit')
                                <button wire:click="toggleStatus({{ $section->id }})" 
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                </button>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                            @endcan
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center space-x-1">
                                @can('sections.edit')
                                    <button wire:click="edit({{ $section->id }})" class="inline-flex items-center justify-center w-7 h-7 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200" title="Редактировать">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                @endcan
                                @can('sections.delete')
                                    <button 
                                        wire:click="delete({{ $section->id }})" 
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-full text-red-600 bg-red-100 hover:bg-red-200 {{ $section->permissions_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        wire:confirm="Вы уверены, что хотите удалить этот раздел?"
                                        {{ $section->permissions_count > 0 ? 'disabled' : '' }}
                                        title="Удалить"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Разделы не найдены</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Мобильная версия (карточки) -->
        <div class="md:hidden">
            <div class="divide-y divide-gray-200">
                @forelse ($sections as $section)
                <div class="p-4 space-y-3">
                    <!-- Заголовок карточки -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            @if($section->icon)
                                <div class="h-5 w-5 text-gray-400">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $section->display_name }}</div>
                                <div class="text-xs text-gray-500">{{ $section->name }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @can('sections.edit')
                                <button wire:click="edit({{ $section->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full text-blue-600 bg-blue-100 hover:bg-blue-200" title="Редактировать">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                            @endcan
                            @can('sections.delete')
                                <button 
                                    wire:click="delete({{ $section->id }})" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-red-600 bg-red-100 hover:bg-red-200 {{ $section->permissions_count > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    wire:confirm="Вы уверены, что хотите удалить этот раздел?"
                                    {{ $section->permissions_count > 0 ? 'disabled' : '' }}
                                    title="Удалить"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endcan
                        </div>
                    </div>

                    <!-- Описание -->
                    @if($section->description)
                    <div class="text-sm text-gray-600">
                        {{ $section->description }}
                    </div>
                    @endif

                    <!-- Детали -->
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Разрешения:</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                {{ $section->permissions_count }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Порядок:</span>
                            <span class="font-medium text-gray-900">{{ $section->sort_order }}</span>
                        </div>
                    </div>

                    <!-- Статус -->
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 text-sm">Статус:</span>
                            @can('sections.edit')
                                <button wire:click="toggleStatus({{ $section->id }})" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                </button>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $section->is_active ? 'Активен' : 'Неактивен' }}
                                </span>
                            @endcan
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 py-12 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <svg class="h-12 w-12 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Разделы не найдены</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $sections->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="closeModal">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            
            <div class="inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg" wire:click.stop>
                <form wire:submit="save">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                {{ $editingSection ? 'Редактировать раздел' : 'Создать раздел' }}
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Имя раздела *</label>
                                <input type="text" wire:model="name" placeholder="например: user_management" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Используйте только строчные буквы и подчеркивания</p>
                                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Отображаемое имя *</label>
                                <input type="text" wire:model="display_name" placeholder="например: Управление пользователями" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('display_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Описание</label>
                                <textarea wire:model="description" placeholder="Краткое описание раздела" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Иконка</label>
                                <input type="text" wire:model="icon" placeholder="например: users, settings, package" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Имя иконки из библиотеки Heroicons</p>
                                @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Порядок сортировки *</label>
                                <input type="number" wire:model="sort_order" min="0" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" 
                                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активен</span>
                                </label>
                                @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" wire:click="closeModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Отмена
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $editingSection ? 'Обновить' : 'Создать' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
