# Task Management Web App

A full-stack task management application built with **Laravel 13**, **Blade**, and **Tailwind CSS v4**. Designed for an internship assessment, the app demonstrates clean MVC architecture, CRUD operations, and a responsive UI with filtering, sorting, and pagination.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Scalability](#scalability)
- [Setup Instructions](#setup-instructions)
- [Database Setup](#database-setup)

---

## Features

- Create, edit, and delete tasks
- Toggle task status between `Pending` or `In Progress` to `Completed`
- Filter tasks by status, priority, and category
- Search tasks by title or description
- Sort tasks by date, priority, title, or status
- Paginated task list with live query string persistence
- Dashboard stats (total, pending, in-progress, completed counts)
- Categorize tasks with color-coded labels
- Overdue task detection
- Form validation with descriptive error messages

---

## Tech Stack

| Layer       | Technology                        |
|-------------|-----------------------------------|
| Backend     | PHP 8.3, Laravel 13               |
| Frontend    | Blade Templates, Tailwind CSS v4  |
| Database    | SQLite (default) / MySQL          |
| Build Tool  | Vite 8                            |
| Testing     | Pest v4                           |

---

## Project Structure

```
Task-Management-Web-App/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php       # Handles all task CRUD + toggle logic
│   │   └── Requests/
│   │       └── TaskRequest.php          # Form validation rules for tasks
│   ├── Models/
│   │   ├── Task.php                     # Task model with scopes & helpers
│   │   ├── Category.php                 # Category model (name, color)
│   │   └── User.php                     # Default Laravel user model
│   └── Providers/
│       └── AppServiceProvider.php
│
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_categories_table.php
│   │   └── ..._create_tasks_table.php   # Tasks schema (status, priority, due_date)
│   ├── factories/
│   │   └── UserFactory.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── resources/
│   ├── css/
│   │   └── app.css                      # Tailwind CSS entry point
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Shared base layout
│       └── tasks/
│           ├── index.blade.php          # Task list with filters & stats
│           ├── create.blade.php         # New task form
│           ├── edit.blade.php           # Edit task form
│           ├── form.blade.php           # Reusable form partial
│           └── card.blade.php           # Reusable task card component
│
├── routes/
│   └── web.php                          # All application routes
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

### Why This Structure?

The project follows Laravel's standard MVC conventions, which keeps responsibilities clearly separated:

- **Controllers** are thin — they delegate filtering logic to the model and validation to `TaskRequest`, keeping each class focused on a single responsibility.
- **Form Requests** (`TaskRequest`) isolate all validation rules away from the controller, making them easier to test and reuse.
- **Model Scopes** (`scopeSearch`, `scopeFilterStatus`, `scopeSorted`, etc.) keep query logic inside the `Task` model where it belongs, rather than leaking into the controller.
- **Blade Partials** (`form.blade.php`, `card.blade.php`) avoid code duplication between the create and edit views.
- **Migrations** define the full database schema in version-controlled files, making database setup reproducible across environments.

This structure ensures new contributors can orient themselves quickly, since it follows the conventions most Laravel developers already know.

---

## Scalability

### How the Project Supports Future Improvement

**Model Scopes are composable.** The `Task` model uses named query scopes (`search`, `filterStatus`, `filterPriority`, `filterCategory`, `sorted`). Adding a new filter — such as an assignee or tag — only requires adding a new scope to the model and wiring it into the controller, with zero impact on existing filters.

**Form validation is decoupled.** `TaskRequest` handles all input rules independently. If business logic becomes more complex (e.g. requiring due dates on high-priority tasks), the rules can be updated in one place without touching controller code.

**Blade components are reusable.** The `form.blade.php` and `card.blade.php` partials are shared between create/edit views. Adding new fields to the task form means editing a single file.

**Database design is extensible.** The `categories` table is already a separate entity with its own model and relationship, making it straightforward to add features like category management pages, per-category stats, or subcategories.

**Testing infrastructure is in place.** Pest is already configured with Feature and Unit test directories. As the app grows, controller, model, and validation tests can be added incrementally.

### Supporting API Integration

The app's structure already makes REST API integration straightforward:

1. **Add an `api.php` routes file** — Laravel supports a dedicated API route file with `prefix('api')` and rate limiting out of the box.
2. **Create API controllers** — the existing `TaskController` logic can be extracted into `Api\TaskController` classes that return `JsonResponse` instead of views, reusing the same models and scopes.
3. **Use API Resources** — Laravel's `JsonResource` classes (e.g. `TaskResource`, `CategoryResource`) can shape JSON responses consistently without touching model logic.
4. **Authentication** — Laravel Sanctum can be added to issue API tokens, enabling mobile apps or third-party integrations to authenticate.

### Additional Features the Architecture Can Support

| Feature | How to Add |
|---|---|
| User authentication & task ownership | Add `user_id` to tasks, use Laravel Breeze/Jetstream |
| Task comments or attachments | New `comments` / `attachments` tables with relationships |
| Email/notification reminders | Use Laravel's built-in Queue + Notification system |
| Recurring tasks | Add a `recurrence` field and a scheduled Artisan command |
| Team / multi-user workspaces | Add a `teams` table; scope all queries by team |
| REST API | Add `routes/api.php` + `Api\TaskController` + API Resources |

---

## Setup Instructions

### Prerequisites

Make sure you have the following installed:

- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 20.x & **npm**
- **Git**

### 1. Clone the Repository

```bash
git clone https://github.com/rubyarthalia/Task-Management-Web-App.git
cd Task-Management-Web-App
```

### 2. Quick Setup (Recommended)

A `composer setup` script is included that handles all installation steps in one command:

```bash
composer setup
```

This will automatically:
- Install PHP dependencies via Composer
- Copy `.env.example` to `.env`
- Generate the application key
- Run all database migrations
- Install Node.js dependencies
- Build frontend assets

### 3. Manual Setup (Step by Step)

If you prefer to run each step individually:

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Install Node dependencies and build assets
npm install
npm run build
```

### 4. Start the Development Server

```bash
composer dev
```

This runs three processes concurrently:
- `php artisan serve` — Laravel backend on `http://localhost:8000`
- `npm run dev` — Vite HMR for frontend assets
- `php artisan queue:listen` — Background job queue

Or start them individually:

```bash
php artisan serve   # http://localhost:8000
npm run dev         # In a separate terminal
```

---

## Database Setup

### Default: SQLite (No Configuration Needed)

By default, the app uses **SQLite**, which requires no database server. The database file is created automatically at `database/database.sqlite`.

Run migrations to create all tables:

```bash
php artisan migrate
```

### Switching to MySQL

1. Create a MySQL database:

```sql
CREATE DATABASE task_management;
```

2. Update your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. Run migrations:

```bash
php artisan migrate
```

### Database Schema

**`categories`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `name` | string | Category name |
| `color` | string(7) | Hex color code, default `#6366f1` |
| `timestamps` | datetime | `created_at`, `updated_at` |

**`tasks`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint | Primary key |
| `category_id` | bigint (FK) | Nullable; sets null on category delete |
| `title` | string | Required, max 255 chars |
| `description` | text | Optional, max 2000 chars |
| `status` | enum | `pending`, `in_progress`, `completed` |
| `priority` | enum | `low`, `medium`, `high` |
| `due_date` | date | Optional |
| `timestamps` | datetime | `created_at`, `updated_at` |

### Running Tests

```bash
composer test
# or
php artisan test
```
