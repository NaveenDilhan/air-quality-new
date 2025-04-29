<div>
    <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" wire:model.defer="{{ $model }}"
        class="w-full p-3 rounded-md border @error($model) border-red-500 @enderror dark:bg-zinc-700 dark:text-white"
        placeholder="{{ $placeholder }}">
    @error($model) <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>