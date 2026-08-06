<?php

namespace App\Livewire\Comments;

use App\Models\Attachment;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public int $commentableId;
    public string $commentableType;

    #[Validate('required|string|max:2000')]
    public ?string $body = null;

    public $files = [];

    public function mount($model)
    {
        $this->commentableId = $model->getKey();
        $this->commentableType = $model->getMorphClass();
    }

    public function save()
    {
        $this->validate([
            'body' => 'required|string|max:2000',
            'files' => 'array|max:5',
            'files.*' => 'file|max:5120',
        ]);

        $comment = Comment::create([
            'commentable_id' => $this->commentableId,
            'commentable_type' => $this->commentableType,
            'body' => $this->body,
            'user_id' => auth()->id(),
        ]);

        foreach ($this->files as $file) {
            $path = $file->store('attachments', 'public');

            Attachment::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'attachable_id' => $comment->id,
                'attachable_type' => Comment::class,
                'user_id' => auth()->id(),
            ]);
        }

        $this->reset(['body', 'files']);
        $this->dispatch('comment-added');
    }

    public function render()
    {
        return view('livewire.comments.create');
    }
}
