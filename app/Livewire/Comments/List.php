<?php

namespace App\Livewire\Comments;

use App\Models\Comment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CommentList extends Component
{
    use WithPagination;

    public int $commentableId;
    public string $commentableType;

    public function mount($model)
    {
        $this->commentableId = $model->getKey();
        $this->commentableType = $model->getMorphClass();
    }

    protected $listeners = [
        'comment-added' => '$refresh',
    ];

    public function delete(Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && ! Gate::allows('manage users')) {
            abort(403);
        }

        $comment->attachments()->each(function ($attachment) {
            Storage::disk('public')->delete($attachment->path);
        });

        $comment->delete();

        $this->dispatch('comment-removed');
    }

    #[Computed]
    public function comments()
    {
        return Comment::query()
            ->where('commentable_id', $this->commentableId)
            ->where('commentable_type', $this->commentableType)
            ->with(['user', 'attachments'])
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.comments.list', [
            'comments' => $this->comments,
        ]);
    }
}
