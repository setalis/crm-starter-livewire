<div class="bg-gray-50 rounded-lg p-4">
    <div class="flex justify-between items-center mb-3">
        <h4 class="text-sm font-medium text-gray-900 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.544-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
            </svg>
            Комментарии ({{ $comments->count() }})
            @if($comments->where('is_read', false)->count() > 0)
                <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $comments->where('is_read', false)->count() }} новых
                </span>
            @endif
        </h4>
        <button wire:click="toggleAddForm" class="text-sm text-blue-600 hover:text-blue-800">
            @if($showAddForm) Отмена @else + Добавить @endif
        </button>
    </div>

    {{-- Форма добавления комментария --}}
    @if($showAddForm)
        <div class="mb-3 p-3 bg-white rounded border">
            <form wire:submit="addComment">
                <div class="space-y-3">
                    <div>
                        <textarea wire:model="content" rows="2" 
                                  class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Введите комментарий..."></textarea>
                        @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <select wire:model="type" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($this->getCommentTypeOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            
                            <label class="flex items-center text-xs text-gray-600">
                                <input type="checkbox" wire:model="isImportant" class="mr-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                Важно
                            </label>
                        </div>

                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700">
                            Добавить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- Список комментариев --}}
    @if($comments->count() > 0)
        <div class="space-y-2 max-h-60 overflow-y-auto">
            @foreach($comments as $comment)
                <div class="bg-white rounded border p-3 {{ !$comment->is_read ? 'border-l-4 border-l-blue-400 bg-blue-50' : '' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $comment->type_css_class }} bg-opacity-10">
                                {{ $comment->type_display_name }}
                            </span>
                            
                            @if($comment->is_important)
                                <span class="px-2 py-0.5 text-xs bg-red-100 text-red-800 rounded-full">
                                    Важно
                                </span>
                            @endif

                            @if(!$comment->is_read)
                                <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">
                                    Новый
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center space-x-1">
                            @if(!$comment->is_read)
                                <button wire:click="markAsRead({{ $comment->id }})" 
                                        class="text-blue-600 hover:text-blue-800 text-xs">
                                    ✓
                                </button>
                            @else
                                <button wire:click="markAsUnread({{ $comment->id }})" 
                                        class="text-gray-600 hover:text-gray-800 text-xs">
                                    ↺
                                </button>
                            @endif

                            @if($comment->user_id === auth()->id() || auth()->user()->can('comments.delete'))
                                <button wire:click="deleteComment({{ $comment->id }})" 
                                        wire:confirm="Удалить комментарий?"
                                        class="text-red-600 hover:text-red-800 text-xs">
                                    ×
                                </button>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-gray-800 mb-2">{{ $comment->content }}</p>

                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $comment->user->name }}</span>
                        <span>{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                    </div>

                    @if($comment->is_read && $comment->readBy)
                        <div class="text-xs text-gray-400 mt-1">
                            Прочитано {{ $comment->readBy->name }} {{ $comment->read_at->format('d.m.Y H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-gray-500 py-4">
            <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.456L3 21l2.544-5.094A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
            </svg>
            <p class="text-sm">Комментариев пока нет</p>
        </div>
    @endif

    {{-- Flash сообщения --}}
    @if (session()->has('comment-message'))
        <div class="mt-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('comment-message') }}
        </div>
    @endif
</div>
