<div class="bg-gray-50 rounded-xl p-6">
    <h4 class="text-sm font-semibold text-gray-700 mb-3">Add a comment</h4>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <textarea name="body" wire:model="body" rows="3"
                placeholder="Write your comment..."
                class="block w-full border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-y"></textarea>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
        </div>

        <div>
            <x-input-label :value="__('Attachments')" />
            <input type="file" name="files[]" wire:model="files" multiple
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-1" />
            <x-input-error :messages="$errors->get('files.*')" class="mt-2" />
            <x-input-error :messages="$errors->get('files')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button wire:loading.attr="disabled" type="submit">
                <span wire:loading inline>Posting…</span>
                <span wire:loading.remove>Post Comment</span>
            </x-primary-button>
        </div>
    </form>
</div>
