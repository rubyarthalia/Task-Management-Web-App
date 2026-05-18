<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = [
            ['name' => 'Work',     'color' => '#6366f1'],
            ['name' => 'Personal', 'color' => '#f59e0b'],
            ['name' => 'Shopping', 'color' => '#10b981'],
            ['name' => 'Health',   'color' => '#ef4444'],
            ['name' => 'Study', 'color' => '#f4f65c'],
            ['name' => 'Others',   'color' => '#44d0ef'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $tasks = [
            [
                'category_id' => 5,
                'title'       => 'Finish DSS Assignment',
                'description' => 'Review and experiment with the code',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'due_date'    => now()->addDays(2)->toDateString(),
            ],
            [
                'category_id' => 3,
                'title'       => 'Buy a new running shoes',
                'description' => 'Check out the latest models and find a good deal online.',
                'status'      => 'pending',
                'priority'    => 'low',
                'due_date'    => now()->addDays(7)->toDateString(),
            ],
            [
                'category_id' => 2,
                'title'       => 'Read a book',
                'description' => 'Finish reading Metamorphosis by Franz Kafka.',
                'status'      => 'pending',
                'priority'    => 'low',
                'due_date'    => now()->addDays(2)->toDateString(),
            ],
            [
                'category_id' => 4,
                'title'       => 'Morning run',
                'description' => '5km run around the neighborhood.',
                'status'      => 'completed',
                'priority'    => 'medium',
                'due_date'    => now()->toDateString(),
            ],
            [
                'category_id' => 3,
                'title'       => 'Buy groceries',
                'description' => 'Milk, eggs, bread, vegetables.',
                'status'      => 'pending',
                'priority'    => 'medium',
                'due_date'    => now()->addDays(2)->toDateString(),
            ],
            [
                'category_id' => 2,
                'title'       => 'Call mom',
                'description' => 'Catch up with mom and discuss the family plans.',
                'status'      => 'pending',
                'priority'    => 'low',
                'due_date'    => now()->addDays()->toDateString(),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
