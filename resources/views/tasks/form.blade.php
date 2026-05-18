{{-- resources/views/tasks/_form.blade.php --}}
{{-- Usage: @include('tasks._form', ['task' => $task, 'categories' => $categories]) --}}

@php $isEdit = isset($task) && $task->exists; @endphp

<div class="space-y-5">

    {{-- Title --}}
    <div>
        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
            Title <span class="text-red-500">*</span>
        </label>
        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title', $task->title ?? '') }}"
            placeholder="What needs to be done?"
            class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                   {{ $errors->has('title') ? 'border-red-400 bg-red-50 dark:bg-red-500/10' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800' }}
                   focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:text-white dark:placeholder-slate-500"
        />
        @error('title')
            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
            Description
        </label>
        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Add more details about this task..."
            class="w-full resize-none rounded-xl border px-4 py-3 text-sm outline-none transition
                   {{ $errors->has('description') ? 'border-red-400 bg-red-50 dark:bg-red-500/10' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800' }}
                   focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:text-white dark:placeholder-slate-500"
        >{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Row: Category + Priority --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

        {{-- Category --}}
        <div>
            <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Category
            </label>
            <select
                id="category_id"
                name="category_id"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition
                       focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
                <option value="">No category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $task->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Priority --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Priority <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-2">
                @foreach(['low' => 'emerald', 'medium' => 'amber', 'high' => 'red'] as $value => $color)
                    <label class="flex flex-1 cursor-pointer flex-col items-center gap-1">
                        <input type="radio" name="priority" value="{{ $value }}" class="sr-only peer"
                               {{ old('priority', $task->priority ?? 'medium') === $value ? 'checked' : '' }}>
                        <span class="w-full rounded-xl border-2 py-2.5 text-center text-xs font-semibold transition
                                     border-slate-200 bg-slate-50 text-slate-500
                                     peer-checked:border-{{ $color }}-400 peer-checked:bg-{{ $color }}-50 peer-checked:text-{{ $color }}-700
                                     dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400
                                     dark:peer-checked:border-{{ $color }}-500 dark:peer-checked:bg-{{ $color }}-500/10 dark:peer-checked:text-{{ $color }}-400
                                     hover:border-{{ $color }}-300">
                            {{ ucfirst($value) }}
                        </span>
                    </label>
                @endforeach
            </div>
            @error('priority')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Row: Status + Due Date --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

        {{-- Status --}}
        <div>
            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Status <span class="text-red-500">*</span>
            </label>
            <select
                id="status"
                name="status"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition
                       focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
            >
                <option value="pending"     {{ old('status', $task->status ?? 'pending') === 'pending'     ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ old('status', $task->status ?? 'pending') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed"   {{ old('status', $task->status ?? 'pending') === 'completed'   ? 'selected' : '' }}>Completed</option>
            </select>
            @error('status')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Due Date --}}
        <div>
            <label for="due_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                Due Date
            </label>
            <input
                type="date"
                id="due_date"
                name="due_date"
                value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition
                       focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white
                       dark:[color-scheme:dark]"
            />
            @error('due_date')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Form actions --}}
    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
        <a href="{{ route('tasks.index') }}"
           class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800">
            Cancel
        </a>
        <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600 active:scale-95">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $isEdit ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}"/>
            </svg>
            {{ $isEdit ? 'Save Changes' : 'Create Task' }}
        </button>
    </div>

</div>