<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const PER_PAGE = 8;

    // Display the task list with search, filter, sort, and pagination.
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'status', 'priority', 'category_id', 'sort', 'direction']);

        $tasks = Task::with('category')
            ->search($filters['search'] ?? null)
            ->filterStatus($filters['status'] ?? null)
            ->filterPriority($filters['priority'] ?? null)
            ->filterCategory(isset($filters['category_id']) ? (int) $filters['category_id'] : null)
            ->sorted($filters['sort'] ?? 'created_at', $filters['direction'] ?? 'desc')
            ->paginate(5)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $stats = [
            'total'       => Task::count(),
            'pending'     => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed'   => Task::where('status', 'completed')->count(),
        ];

        return view('tasks.index', compact('tasks', 'categories', 'filters', 'stats'));
    }

    // Create a new task.
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    // Edit an existing task.
    public function edit(Task $task): View
    {
        $categories = Category::orderBy('name')->get();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(TaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    //Toggle a task's status between pending and completed.
    public function toggleStatus(Task $task): RedirectResponse
    {
        $task->update([
            'status' => $task->isCompleted() ? 'pending' : 'completed',
        ]);

        return back()->with('success', 'Task status updated!');
    }

    // Delete a task.
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }
}