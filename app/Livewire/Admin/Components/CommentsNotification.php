<?php

namespace App\Livewire\Admin\Components;

use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\On;

class CommentsNotification extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    #[On('comment-added')]
    #[On('comment-read')]
    #[On('comments-marked-as-read')]
    public function updateUnreadCount()
    {
        // Проверяем право просмотра комментариев
        if (!auth()->user()->can('comments.view')) {
            $this->unreadCount = 0;
            return;
        }

        $query = Comment::unread();
        
        // Если нет прав на просмотр всех комментариев, показываем только свои
        if (!auth()->user()->can('comments.all')) {
            $query->where('user_id', auth()->id());
        }

        $this->unreadCount = $query->count();
    }

    public function render()
    {
        return view('livewire.admin.components.comments-notification');
    }
} 