@extends('layouts.app')
@section('title', 'New Task')

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
        <h1 class="font-display text-3xl font-bold text-slate-900 dark:text-white">New Task</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Fill in the details to create a new task.</p>
    </div>

    {{-- Form card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            @include('tasks.form', ['categories' => $categories])
        </form>
    </div>

</div>
@endsection