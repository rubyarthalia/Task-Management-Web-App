@extends('layouts.app')
@section('title', 'All Tasks')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-3xl font-bold text-slate-900 dark:text-white">My Tasks</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage and track all your tasks in one place.</p>
        </div>
        <a href="{{ route('tasks.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600 active:scale-95">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Task
        </a>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach([
            ['label' => 'Total',       'value' => $stats['total'],       'color' => 'brand',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Pending',     'value' => $stats['pending'],     'color' => 'slate',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'In Progress', 'value' => $stats['in_progress'], 'color' => 'blue',    'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['label' => 'Completed',   'value' => $stats['completed'],   'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $stat)
        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                <div class="rounded-lg bg-{{ $stat['color'] }}-50 p-1.5 dark:bg-{{ $stat['color'] }}-500/10">
                    <svg class="h-4 w-4 text-{{ $stat['color'] }}-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="mt-2 font-display text-2xl font-bold text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ===== FILTERS & SEARCH ===== --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900" x-data="{ filtersOpen: {{ collect($filters)->filter()->isNotEmpty() ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('tasks.index') }}" id="filter-form">

            {{-- Search bar --}}
            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search tasks... (press Enter)"
                        x-on:keydown.enter="$el.closest('form').submit()"
                        x-on:input="if ($el.value === '') $el.closest('form').submit()"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-4 text-sm outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-400/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder-slate-500"
                    />
                </div>

                <button type="button" @click="filtersOpen = !filtersOpen"
                    class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filters
                    @if(collect($filters)->except('search')->filter()->isNotEmpty())
                        <span class="flex h-4 w-4 items-center justify-center rounded-full bg-brand-500 text-[10px] font-bold text-white">
                            {{ collect($filters)->except('search')->filter()->count() }}
                        </span>
                    @endif
                </button>

                {{-- Sort --}}
                <select name="sort"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <option value="created_at" {{ ($filters['sort'] ?? '') === 'created_at' ? 'selected' : '' }}>Sort: Date Created</option>
                    <option value="due_date"   {{ ($filters['sort'] ?? '') === 'due_date'   ? 'selected' : '' }}>Sort: Due Date</option>
                    <option value="priority"   {{ ($filters['sort'] ?? '') === 'priority'   ? 'selected' : '' }}>Sort: Priority</option>
                    <option value="title"      {{ ($filters['sort'] ?? '') === 'title'      ? 'selected' : '' }}>Sort: Title</option>
                </select>

                <select name="direction"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <option value="desc" {{ ($filters['direction'] ?? 'desc') === 'desc' ? 'selected' : '' }}>↓ Desc</option>
                    <option value="asc"  {{ ($filters['direction'] ?? 'desc') === 'asc'  ? 'selected' : '' }}>↑ Asc</option>
                </select>

                {{-- Apply button --}}
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600 active:scale-95">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Apply
                </button>
            </div>

            {{-- Expanded filters --}}
            <div x-show="filtersOpen" x-cloak x-transition class="mt-3 grid grid-cols-1 gap-3 border-t border-slate-100 pt-3 sm:grid-cols-3 dark:border-slate-800">

                {{-- Status --}}
                <select name="status"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <option value="">All Statuses</option>
                    <option value="pending"     {{ ($filters['status'] ?? '') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ ($filters['status'] ?? '') === 'completed'   ? 'selected' : '' }}>Completed</option>
                </select>

                {{-- Priority --}}
                <select name="priority"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <option value="">All Priorities</option>
                    <option value="high"   {{ ($filters['priority'] ?? '') === 'high'   ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low"    {{ ($filters['priority'] ?? '') === 'low'    ? 'selected' : '' }}>Low</option>
                </select>

                {{-- Category --}}
                <select name="category_id"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600 outline-none focus:border-brand-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Clear filters --}}
            @if(collect($filters)->filter()->isNotEmpty())
                <div class="mt-3 flex justify-end">
                    <a href="{{ route('tasks.index') }}" class="text-xs font-medium text-brand-500 hover:underline">
                        Clear all filters
                    </a>
                </div>
            @endif

        </form>
    </div>

    {{-- ===== TASK GRID ===== --}}
    @if($tasks->isEmpty())
        {{-- Empty state --}}
        <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white py-20 dark:border-slate-700 dark:bg-slate-900">
            <div class="rounded-full bg-brand-50 p-5 dark:bg-brand-500/10">
                <svg class="h-10 w-10 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="mt-4 font-display text-lg font-semibold text-slate-700 dark:text-slate-300">No tasks found</h3>
            <p class="mt-1 text-sm text-slate-400">
                {{ collect($filters)->filter()->isNotEmpty() ? 'Try adjusting your filters.' : 'Create your first task to get started.' }}
            </p>
            @if(collect($filters)->filter()->isEmpty())
                <a href="{{ route('tasks.create') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Task
                </a>
            @endif
        </div>
    @else
        {{-- Column headers --}}
        <div class="hidden sm:flex items-center gap-4 px-5 pb-1 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            <div class="w-5 flex-shrink-0"></div>
            <div class="flex-1">Task</div>
            <div class="flex-shrink-0 text-right">Category · Priority · Status · Due</div>
            <div class="w-16 flex-shrink-0"></div>
        </div>

        <div class="flex flex-col gap-2">
            @foreach($tasks as $task)
                @include('tasks.card', ['task' => $task])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($tasks->hasPages())
            <div class="flex items-center justify-between border-t border-slate-200 pt-4 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                </p>
                <div class="flex items-center gap-1">
                    {{-- Previous --}}
                    @if($tasks->onFirstPage())
                        <span class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-300 dark:border-slate-700 dark:text-slate-600">← Prev</span>
                    @else
                        <a href="{{ $tasks->previousPageUrl() }}"
                           class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800">
                            ← Prev
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($tasks->getUrlRange(1, $tasks->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="rounded-xl border px-3 py-2 text-sm transition
                               {{ $page === $tasks->currentPage()
                                   ? 'border-brand-500 bg-brand-500 text-white'
                                   : 'border-slate-200 text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if($tasks->hasMorePages())
                        <a href="{{ $tasks->nextPageUrl() }}"
                           class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800">
                            Next →
                        </a>
                    @else
                        <span class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-300 dark:border-slate-700 dark:text-slate-600">Next →</span>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>
@endsection