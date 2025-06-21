<div>
    {{-- Header с фильтрами --}}
    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">
                {{ $activeTab === 'user' ? 'Пользовательские комментарии' : 'Системные сообщения' }}
                @if(($activeTab === 'user' && $stats['user_unread'] > 0) || ($activeTab === 'system' && $stats['system_unread'] > 0))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ $activeTab === 'user' ? $stats['user_unread'] : $stats['system_unread'] }} непрочитанных
                    </span>
                @endif
            </h2>
            <div class="flex space-x-2">
                @can('comments.manage')
                    @if(($activeTab === 'user' && $stats['user_unread'] > 0) || ($activeTab === 'system' && $stats['system_unread'] > 0))
                        <button wire:click="markAllAsRead" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Отметить все как прочитанные
                        </button>
                    @endif
                @endcan
                @can('comments.create')
                    @if($activeTab === 'user')
                        <button wire:click="openModal" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Добавить комментарий
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        {{-- Табы --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <button wire:click="setActiveTab('user')"
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'user' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Пользовательские комментарии
                    @if($stats['user_total'] > 0)
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['user_total'] }}</span>
                    @endif
                    @if($stats['user_unread'] > 0)
                        <span class="ml-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['user_unread'] }} новых</span>
                    @endif
                </button>
                <button wire:click="setActiveTab('system')"
                        class="py-2 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'system' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Системные сообщения
                    @if($stats['system_total'] > 0)
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['system_total'] }}</span>
                    @endif
                    @if($stats['system_unread'] > 0)
                        <span class="ml-1 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs">{{ $stats['system_unread'] }} новых</span>
                    @endif
                </button>
            </nav>
        </div>

        {{-- Статистика --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-sm font-medium text-blue-900">Всего {{ $activeTab === 'user' ? 'пользовательских' : 'системных' }}</div>
                <div class="text-2xl font-bold text-blue-600">{{ $activeTab === 'user' ? $stats['user_total'] : $stats['system_total'] }}</div>
            </div>
            <div class="bg-red-50 rounded-lg p-3">
                <div class="text-sm font-medium text-red-900">Непрочитанные</div>
                <div class="text-2xl font-bold text-red-600">{{ $activeTab === 'user' ? $stats['user_unread'] : $stats['system_unread'] }}</div>
            </div>
            @if($activeTab === 'user')
            <div class="bg-yellow-50 rounded-lg p-3">
                <div class="text-sm font-medium text-yellow-900">Важные</div>
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['important'] }}</div>
            </div>
            @else
            <div class="bg-purple-50 rounded-lg p-3">
                <div class="text-sm font-medium text-purple-900">Автоматические</div>
                <div class="text-2xl font-bold text-purple-600">{{ $stats['system_total'] }}</div>
            </div>
            @endif
            <div class="bg-green-50 rounded-lg p-3">
                <div class="text-sm font-medium text-green-900">За неделю</div>
                <div class="text-2xl font-bold text-green-600">{{ $stats['recent'] }}</div>
            </div>
        </div>

        {{-- Фильтры --}}
        <div class="grid grid-cols-1 md:grid-cols-{{ $activeTab === 'user' ? '6' : '4' }} gap-4">
            <div>
                <input type="text" wire:model.live="search" placeholder="Поиск по содержимому..."
                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            @if($activeTab === 'user')
            <div>
                <select wire:model.live="filterType" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все типы</option>
                    <option value="comment">Комментарий</option>
                    <option value="note">Заметка</option>
                    <option value="warning">Предупреждение</option>
                    <option value="info">Информация</option>
                </select>
            </div>
            @endif
            <div>
                <select wire:model.live="filterIsRead" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все</option>
                    <option value="0">Непрочитанные</option>
                    <option value="1">Прочитанные</option>
                </select>
            </div>
            @if($activeTab === 'user')
            <div>
                <select wire:model.live="filterImportant" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все</option>
                    <option value="1">Важные</option>
                    <option value="0">Обычные</option>
                </select>
            </div>
            @endif
            <div>
                <select wire:model.live="filterUser" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все пользователи</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="filterSection" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Все разделы</option>
                    @foreach($sections as $sectionType => $count)
                        <option value="{{ $sectionType }}">{{ $this->getSectionName($sectionType) }} ({{ $count }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Список комментариев --}}
    <div class="bg-white rounded-lg shadow-sm">
        @if($comments->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($comments as $comment)
                    <div class="p-6 {{ !$comment->is_read ? 'bg-blue-50 border-l-4 border-blue-400' : '' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                {{-- Заголовок комментария --}}
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $comment->type_css_class }} bg-opacity-10">
                                        {{ $comment->type_display_name }}
                                    </span>
                                    
                                    @if($comment->is_important)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                            Важно
                                        </span>
                                    @endif

                                    @if(!$comment->is_read)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Новый
                                        </span>
                                    @endif
                                </div>

                                {{-- Содержимое комментария --}}
                                <p class="text-gray-800 mb-3">{{ $comment->content }}</p>

                                {{-- Информация о связанной сущности --}}
                                @if($comment->commentable)
                                    <div class="text-sm text-gray-600 mb-2">
                                        <span class="font-medium">Относится к:</span> {{ $comment->commentable_description }}
                                    </div>
                                @endif

                                {{-- Метаинформация --}}
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $comment->user->name }}</span>
                                    <span>{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                                    @if($comment->is_read && $comment->readBy)
                                        <span>Прочитано {{ $comment->readBy->name }} {{ $comment->read_at->format('d.m.Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Действия --}}
                            <div class="flex items-center space-x-2 ml-4">
                                @can('comments.manage')
                                    @if(!$comment->is_read)
                                        <button wire:click="markAsRead({{ $comment->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                            Прочитано
                                        </button>
                                    @else
                                        <button wire:click="markAsUnread({{ $comment->id }})" 
                                                class="text-gray-600 hover:text-gray-800 text-sm">
                                            Не прочитано
                                        </button>
                                    @endif
                                @endcan

                                @if($comment->user_id === auth()->id() || auth()->user()->can('comments.delete'))
                                    <button wire:click="deleteComment({{ $comment->id }})" 
                                            wire:confirm="Вы уверены, что хотите удалить этот комментарий?"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        Удалить
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Пагинация --}}
            <div class="px-6 py-4 bg-gray-50">
                {{ $comments->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.544-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    {{ $activeTab === 'user' ? 'Пользовательских комментариев пока нет' : 'Системных сообщений пока нет' }}
                </h3>
                <p class="text-gray-500">
                    @if($activeTab === 'user')
                        Создайте первый комментарий для отслеживания важной информации.
                    @else
                        Системные сообщения появятся автоматически при выполнении операций.
                    @endif
                </p>
            </div>
        @endif
    </div>

    {{-- Модальное окно добавления комментария --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="addComment">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Добавить комментарий
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Содержимое</label>
                                    <textarea wire:model="content" rows="4" 
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Введите комментарий..."></textarea>
                                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Тип</label>
                                    <select wire:model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($this->getCommentTypeOptions() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" wire:model="isImportant" id="isImportant" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="isImportant" class="ml-2 text-sm text-gray-700">Важный комментарий</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Добавить
                            </button>
                            <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Flash сообщения --}}
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif
</div>
