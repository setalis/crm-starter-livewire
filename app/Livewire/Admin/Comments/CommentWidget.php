<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class CommentWidget extends Component
{
    public $model;
    public $modelClass;
    public $modelId;
    public $showAddForm = false;
    public $content = '';
    public $type = 'comment';
    public $isImportant = false;

    protected $rules = [
        'content' => 'required|string|min:3|max:1000',
        'type' => 'required|in:comment,note,warning,info',
        'isImportant' => 'boolean',
    ];

    public function mount(Model $model)
    {
        $this->model = $model;
        $this->modelClass = get_class($model);
        $this->modelId = $model->id;
    }

    public function render()
    {
        $comments = $this->model->comments()->with(['user', 'readBy'])->get();

        return view('livewire.admin.comments.comment-widget', [
            'comments' => $comments,
        ]);
    }

    public function toggleAddForm()
    {
        $this->showAddForm = !$this->showAddForm;
        if (!$this->showAddForm) {
            $this->resetForm();
        }
    }

    public function addComment()
    {
        $this->validate();

        $this->model->addComment(
            $this->content,
            $this->type,
            $this->isImportant
        );

        $this->resetForm();
        $this->showAddForm = false;
        session()->flash('comment-message', 'Комментарий добавлен!');
    }

    public function markAsRead($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->markAsRead();
        }
    }

    public function markAsUnread($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment) {
            $comment->markAsUnread();
        }
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && ($comment->user_id === auth()->id() || auth()->user()->can('comments.delete'))) {
            $comment->delete();
            session()->flash('comment-message', 'Комментарий удален!');
        }
    }

    private function resetForm()
    {
        $this->content = '';
        $this->type = 'comment';
        $this->isImportant = false;
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
}
