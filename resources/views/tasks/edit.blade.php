@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="mx-auto max-w-2xl">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}"
           class="mb-4 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-brand-500 dark:text-slate-400">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to tasks
        </a>
        <h1 class="font-display text-3xl font-bold text-slate-900 dark:text-white">Edit Task</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Update the details for this task.</p>
    </div>

    {{-- Form card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            @include('tasks.form', ['task' => $task, 'categories' => $categories])
        </form>
    </div>

    {{-- Danger zone --}}
    <div class="mt-4 rounded-2xl border border-red-100 bg-red-50 p-4 dark:border-red-900/40 dark:bg-red-500/5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-red-700 dark:text-red-400">Delete this task</p>
                <p class="text-xs text-red-500 dark:text-red-500/70">This action cannot be undone.</p>
            </div>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                  x-data
                  @submit.prevent="if(confirm('Are you sure you want to delete this task?')) $el.submit()">
                @csrf @method('DELETE')
                <button type="submit"
                    class="rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600 active:scale-95">
                    Delete Task
                </button>
            </form>
        </div>
    </div>

</div>
@endsection