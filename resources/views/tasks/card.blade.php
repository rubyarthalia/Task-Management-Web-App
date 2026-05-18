{{-- resources/views/tasks/_card.blade.php --}}
{{-- Usage: @include('tasks._card', ['task' => $task]) --}}

@php
    $priorityClasses = [
        'high'   => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
        'medium' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
        'low'    => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
    ];
    $statusClasses = [
        'pending'     => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
        'completed'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
    ];
@endphp

<div class="group flex items-center gap-4 rounded-2xl border bg-white px-5 py-4 transition-all duration-200
            hover:shadow-md
            {{ $task->isCompleted() ? 'opacity-60' : '' }}
            {{ $task->isOverdue() ? 'border-red-200 dark:border-red-800' : 'border-slate-200 dark:border-slate-700' }}
            dark:bg-slate-900">

    {{-- Toggle complete checkbox --}}
    <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="flex-shrink-0">
        @csrf @method('PATCH')
        <button type="submit"
            title="{{ $task->isCompleted() ? 'Mark as Pending' : 'Mark as Completed' }}"
            class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition
                   {{ $task->isCompleted()
                       ? 'border-emerald-500 bg-emerald-500 text-white'
                       : 'border-slate-300 hover:border-brand-400 dark:border-slate-600' }}">
            @if($task->isCompleted())
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            @endif
        </button>
    </form>

    {{-- Main content --}}
    <div class="flex flex-1 flex-col gap-1 min-w-0 sm:flex-row sm:items-center sm:gap-4">

        {{-- Title + description --}}
        <div class="flex-1 min-w-0">
            <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100
                      {{ $task->isCompleted() ? 'line-through text-slate-400 dark:text-slate-500' : '' }}">
                {{ $task->title }}
            </p>
            @if($task->description)
                <p class="mt-0.5 truncate text-xs text-slate-400 dark:text-slate-500">
                    {{ $task->description }}
                </p>
            @endif
        </div>

        {{-- Meta: category, priority, status, due date --}}
        <div class="flex flex-wrap items-center gap-2 flex-shrink-0">

            {{-- Category --}}
            @if($task->category)
                <span class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                    <span class="h-2 w-2 rounded-full flex-shrink-0" style="background-color: {{ $task->category->color }}"></span>
                    {{ $task->category->name }}
                </span>
            @endif

            {{-- Priority --}}
            <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $priorityClasses[$task->priority] }}">
                {{ ucfirst($task->priority) }}
            </span>

            {{-- Status --}}
            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses[$task->status] }}">
                {{ $task->statusLabel() }}
            </span>

            {{-- Due date --}}
            @if($task->due_date)
                <span class="flex items-center gap-1 text-xs {{ $task->isOverdue() ? 'text-red-500 dark:text-red-400 font-medium' : 'text-slate-400 dark:text-slate-500' }}">
                    <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $task->due_date->format('M d, Y') }}
                    @if($task->isOverdue())
                        <span class="rounded-full bg-red-100 px-1.5 py-0.5 text-[10px] font-semibold text-red-600 dark:bg-red-500/15">Overdue</span>
                    @endif
                </span>
            @endif
        </div>
    </div>

    {{-- Action buttons --}}
    <div class="flex items-center gap-1 flex-shrink-0 opacity-0 transition-opacity group-hover:opacity-100">

        {{-- Edit --}}
        <a href="{{ route('tasks.edit', $task) }}"
           title="Edit"
           class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-brand-600 dark:hover:bg-slate-800 dark:hover:text-brand-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </a>

        {{-- Delete --}}
        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
              x-data
              @submit.prevent="if(confirm('Delete this task?')) $el.submit()">
            @csrf @method('DELETE')
            <button type="submit"
                title="Delete"
                class="rounded-lg p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
    </div>

</div>