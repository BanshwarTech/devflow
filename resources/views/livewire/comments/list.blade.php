<div class="space-y-4">
    @forelse ($comments as $comment)
        <div class="border border-gray-100 rounded-lg p-4 bg-gray-50">
            <div class="flex items-start gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-sm text-gray-800">{{ $comment->user?->name ?? 'Anonymous' }}</span>
                        <span class="text-xs text-gray-400">{{ $comment->created_at?->diffForHumans() }}</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->body }}</p>

                    @if ($comment->attachments->isNotEmpty())
                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($comment->attachments as $attachment)
                                @php($isImage = Str::startsWith($attachment->mime_type, 'image'))
                                @if ($isImage)
                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="block">
                                        <img src="{{ Storage::url($attachment->path) }}" alt="{{ $attachment->name }}"
                                            class="object-cover w-full h-20 rounded border" />
                                    </a>
                                @else
                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank"
                                        class="flex items-center gap-2 p-2 border rounded hover:bg-gray-100">
                                        <span class="text-xs text-gray-600 truncate">{{ Str::limit($attachment->name, 20) }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex flex-col items-end gap-1">
                    @if ($comment->user_id === auth()->id() || @can('manage users'))
                        <button type="button" wire:click="delete({{ $comment->id }})"
                            wire:confirm="Delete this comment?"
                            class="text-xs text-rose-600 hover:text-rose-800">Delete</button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400">No comments yet. Be the first to comment.</p>
    @endforelse

    {{ $comments->links() }}
</div>
