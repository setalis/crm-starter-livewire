<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;

class CommentManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $content = '';
    public $type = 'comment';
    public $isImportant = false;
    public $commentableModel = null;
    public $commentableId = null;

    // Фильтры
    public $filterType = '';
    public $filterIsRead = '';
    public $filterImportant = '';
    public $filterUser = '';
    public $filterSection = '';
    public $search = '';

    // Активная вкладка
    public $activeTab = 'user'; // 'user' или 'system'

    protected $rules = [
        'content' => 'required|string|min:3|max:1000',
        'type' => 'required|in:comment,note,warning,info',
        'isImportant' => 'boolean',
    ];

    public function render()
    {
        // Проверяем базовое право просмотра комментариев
        if (!auth()->user()->can('comments.view')) {
            abort(403, 'У вас нет прав на просмотр комментариев');
        }

        $query = Comment::with(['user', 'readBy', 'commentable'])
            ->latest();

        // Фильтруем по активной вкладке
        if ($this->activeTab === 'user') {
            $query->where('type', '!=', 'system');
            
            // Если нет прав на просмотр всех комментариев, показываем только свои
            if (!auth()->user()->can('comments.all')) {
                $query->where('user_id', auth()->id());
            }
        } else {
            // Проверяем право на просмотр системных сообщений
            if (!auth()->user()->can('comments.system')) {
                abort(403, 'У вас нет прав на просмотр системных сообщений');
            }
            $query->where('type', 'system');
        }

        // Применяем фильтры
        if ($this->filterType && $this->activeTab === 'user') {
            $query->where('type', $this->filterType);
        }

        if ($this->filterIsRead !== '') {
            $query->where('is_read', $this->filterIsRead === '1');
        }

        if ($this->filterImportant !== '') {
            $query->where('is_important', $this->filterImportant === '1');
        }

        if ($this->filterUser) {
            $query->where('user_id', $this->filterUser);
        }

        if ($this->filterSection) {
            $query->where('commentable_type', $this->filterSection);
        }

        if ($this->search) {
            $query->where('content', 'like', '%' . $this->search . '%');
        }

        $comments = $query->paginate(20);

        // Получаем пользователей для фильтра
        $users = \App\Models\User::select('id', 'name')->get();

        // Получаем разделы для фильтра
        $sections = Comment::selectRaw('commentable_type, COUNT(*) as count')
            ->whereNotNull('commentable_type')
            ->groupBy('commentable_type')
            ->pluck('count', 'commentable_type')
            ->toArray();

        // Статистика комментариев
        $stats = [
            'total' => Comment::count(),
            'user_total' => Comment::where('type', '!=', 'system')->count(),
            'system_total' => Comment::where('type', 'system')->count(),
            'unread' => Comment::unread()->count(),
            'user_unread' => Comment::unread()->where('type', '!=', 'system')->count(),
            'system_unread' => Comment::unread()->where('type', 'system')->count(),
            'important' => Comment::important()->count(),
            'recent' => Comment::recent(7)->count(),
            'by_type' => Comment::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];

        return view('livewire.admin.comments.comment-manager', [
            'comments' => $comments,
            'users' => $users,
            'sections' => $sections,
            'stats' => $stats,
        ]);
    }

    public function openModal($commentableType = null, $commentableId = null)
    {
        $this->commentableModel = $commentableType;
        $this->commentableId = $commentableId;
        $this->showModal = true;
        $this->resetInputFields();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->content = '';
        $this->type = 'comment';
        $this->isImportant = false;
    }

    public function addComment()
    {
        // Проверяем право создания комментариев
        if (!auth()->user()->can('comments.create')) {
            session()->flash('error', 'У вас нет прав на создание комментариев');
            return;
        }

        $this->validate();

        // Если указана конкретная модель, добавляем к ней
        if ($this->commentableModel && $this->commentableId) {
            $modelClass = $this->commentableModel;
            $model = $modelClass::find($this->commentableId);

            if ($model) {
                $model->addComment(
                    $this->content,
                    $this->type,
                    $this->isImportant
                );
            }
        } else {
            // Создаем независимый комментарий
            Comment::create([
                'user_id' => auth()->id(),
                'content' => $this->content,
                'type' => $this->type,
                'is_important' => $this->isImportant,
            ]);
        }

        $this->closeModal();
        $this->dispatch('comment-added');
        session()->flash('message', 'Комментарий успешно добавлен!');
    }

    public function markAsRead($commentId)
    {
        // Проверяем право управления статусом прочтения
        if (!auth()->user()->can('comments.manage')) {
            session()->flash('error', 'У вас нет прав на управление статусом комментариев');
            return;
        }

        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->markAsRead();
            $this->dispatch('comment-read');
        }
    }

    public function markAsUnread($commentId)
    {
        // Проверяем право управления статусом прочтения
        if (!auth()->user()->can('comments.manage')) {
            session()->flash('error', 'У вас нет прав на управление статусом комментариев');
            return;
        }

        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->markAsUnread();
            $this->dispatch('comment-read');
        }
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        
        if (!$comment) {
            session()->flash('error', 'Комментарий не найден');
            return;
        }

        // Проверяем права: либо это свой комментарий, либо есть право удаления
        if ($comment->user_id === auth()->id() || auth()->user()->can('comments.delete')) {
            $comment->delete();
            session()->flash('message', 'Комментарий удален!');
        } else {
            session()->flash('error', 'У вас нет прав на удаление этого комментария');
        }
    }

    public function markAllAsRead()
    {
        // Проверяем право управления статусом прочтения
        if (!auth()->user()->can('comments.manage')) {
            session()->flash('error', 'У вас нет прав на управление статусом комментариев');
            return;
        }

        $query = Comment::unread();
        
        // Фильтруем по активной вкладке
        if ($this->activeTab === 'user') {
            $query->where('type', '!=', 'system');
            
            // Если нет прав на просмотр всех комментариев, отмечаем только свои
            if (!auth()->user()->can('comments.all')) {
                $query->where('user_id', auth()->id());
            }
        } else {
            $query->where('type', 'system');
        }
        
        $query->update([
            'is_read' => true,
            'read_at' => now(),
            'read_by' => auth()->id(),
        ]);

        $this->dispatch('comments-marked-as-read');
        $tabName = $this->activeTab === 'user' ? 'пользовательские комментарии' : 'системные сообщения';
        session()->flash('message', "Все {$tabName} отмечены как прочитанные!");
    }



    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterIsRead()
    {
        $this->resetPage();
    }

    public function updatedFilterImportant()
    {
        $this->resetPage();
    }

    public function updatedFilterUser()
    {
        $this->resetPage();
    }

    public function updatedFilterSection()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        
        // Сбрасываем некоторые фильтры при переключении табов
        if ($tab === 'system') {
            $this->filterType = '';
            $this->filterImportant = '';
        }
    }

    public function getCommentTypeOptions()
    {
        return [
            'comment' => 'Комментарий',
            'note' => 'Заметка',
            'warning' => 'Предупреждение',
            'info' => 'Информация',
        ];
    }

    public function getSectionName($sectionType)
    {
        return match($sectionType) {
            'App\Models\Product' => 'Продукты',
            'App\Models\Element' => 'Элементы',
            'App\Models\Operation' => 'Операции',
            'App\Models\Shipment' => 'Отгрузки',
            'App\Models\CashTransaction' => 'Касса',
            'App\Models\Conversion' => 'Конвертации',
            default => 'Неизвестно',
        };
    }
}
