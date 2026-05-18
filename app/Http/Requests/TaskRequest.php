<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'priority'    => ['required', 'in:low,medium,high'],
            'status'      => ['required', 'in:pending,in_progress,completed'],
            'due_date'    => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'A task title is required.',
            'title.max'         => 'Title must not exceed 255 characters.',
            'category_id.exists'=> 'Selected category does not exist.',
            'priority.in'       => 'Priority must be low, medium, or high.',
            'status.in'         => 'Status must be pending, in_progress, or completed.',
            'due_date.date'     => 'Due date must be a valid date.',
        ];
    }
}