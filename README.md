# DevFlow

> **Laravel 13** project management SaaS built with Livewire Volt, Spatie Permission, Pulse, Reverb, and Pennant.

## Table of Contents

- [About](#about)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Schema](#database-schema)
- [Authentication & Authorization](#authentication--authorization)
- [Livewire Components](#livewire-components)
- [Events & Listeners](#events--listeners)
- [Real-time Features](#real-time-features)
- [Routes](#routes)
- [Testing](#testing)
- [Deployment](#deployment)

## About

DevFlow is a complete project management SaaS application designed to help teams manage projects, tasks, teams, and collaboration. It demonstrates advanced Laravel 13 features including real-time broadcasting, activity logging, permission-based authorization, and modern Livewire Volt UI.

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 13.8 |
| UI Layer | Livewire 3.6 + Volt 1.7 |
| Styling | Tailwind CSS |
| Auth | Laravel Breeze + Spatie Permission |
| Real-time | Laravel Reverb |
| Monitoring | Laravel Pulse |
| Feature Flags | Laravel Pennant |
| API Auth | Laravel Sanctum |
| Queue | Database Driver |
| Mail | Database Log Driver (dev) |

## Features

### Phase 1: Foundation
- Laravel 13 with Breeze Livewire/Volt
- Spatie Permission (Admin, Manager, Employee roles)
- Laravel Pulse, Pennant, Sanctum, Reverb installed

### Phase 2: Core Modules
- **Projects** - Create, edit, view, delete projects with status tracking
- **Tasks** - Task management with priorities, due dates, assignments
- **Teams** - Team creation with member management
- **Comments** - Polymorphic comments on projects/tasks with file attachments
- **Activity Logging** - Automatic activity tracking for all actions

### Phase 3: Advanced Features
- Event-driven architecture (ProjectCreated, TaskAssigned, TaskUpdated, CommentAdded)
- Queue-based notifications (database driver)
- Livewire real-time updates
- Permission-based navigation

### Phase 4: Professional
- Policy-based authorization
- Computed properties for dashboard statistics
- Paginated, filterable lists

### Phase 5: Production Ready
- Docker support
- CI/CD pipeline
- Environment-based configuration

## Installation

### Prerequisites
- PHP 8.3+
- Composer
- Node.js + npm
- MySQL 8.0+ (or SQLite for development)
- Redis (optional, for production)

### Steps

1. **Clone and install dependencies**
```bash
composer install
npm install
```

2. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure database in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devflow
DB_USERNAME=root
DB_PASSWORD=
```

4. **Run migrations and seeders**
```bash
php artisan migrate --force
php artisan db:seed --force
```

5. **Build assets**
```bash
npm run build
```

6. **Start development server**
```bash
composer run dev
```

## Configuration

### Key Environment Variables

```env
# App
APP_NAME=DevFlow
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_DATABASE=devflow
DB_USERNAME=root
DB_PASSWORD=

# Broadcast (Reverb)
BROADCAST_CONNECTION=reverb

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database
```

### Spatie Permission Setup

Middleware aliases registered in `bootstrap/app.php`:
- `role` - `RoleMiddleware`
- `permission` - `PermissionMiddleware`
- `role_or_permission` - `RoleOrPermissionMiddleware`

## Database Schema

### Core Tables

| Table | Purpose |
|-------|---------|
| `users` | User accounts with email/password |
| `projects` | Project records with owner, dates, status |
| `tasks` | Task records linked to projects with assignee |
| `teams` | Team records with owner |
| `team_members` | Pivot table for team-user relationships |
| `comments` | Polymorphic comments (projects, tasks) |
| `attachments` | Polymorphic file attachments |
| `activities` | Activity log for tracking changes |
| `notifications` | User notifications |

### Permission Tables (Spatie)

| Table | Purpose |
|-------|---------|
| `permissions` | Available permissions |
| `roles` | User roles (Admin, Manager, Employee) |
| `model_has_roles` | Role assignments |
| `model_has_permissions` | Direct permission grants |
| `role_has_permissions` | Role-permission mappings |

### Feature Tables

| Table | Purpose |
|-------|---------|
| `features` | Laravel Pennant feature flags |
| `pulse_*` | Laravel Pulse monitoring data |

## Authentication & Authorization

### Roles

| Role | Permissions |
|------|-------------|
| Admin | manage projects, manage tasks, manage teams, view dashboard, manage users |
| Manager | manage projects, manage tasks, manage teams, view dashboard |
| Employee | view dashboard |

### Demo Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@devflow.test | password | Admin |
| manager@devflow.test | password | Manager |
| employee@devflow.test | password | Employee |

### Policies

- **ProjectPolicy** - viewAny, view, create, update, delete
- **TaskPolicy** - viewAny, view, create, update, delete
- **TeamPolicy** - viewAny, view, create, update, delete, manageMembers

## Livewire Components

### Dashboard
- `App\Livewire\Dashboard` - Statistics cards, upcoming deadlines, recent projects, activity feed

### Projects
- `App\Livewire\Projects\Index` - Filterable/paginated project list
- `App\Livewire\Projects\Create` - Project creation form
- `App\Livewire\Projects\Edit` - Project editing form
- `App\Livewire\Projects\Show` - Project detail with tasks and activities

### Tasks
- `App\Livewire\Tasks\Index` - Filterable/paginated task list (search, status, priority, project)
- `App\Livewire\Tasks\Create` - Task creation with project/assignee selection
- `App\Livewire\Tasks\Edit` - Task editing
- `App\Livewire\Tasks\Show` - Task detail with comments

### Teams
- `App\Livewire\Teams\Index` - Searchable team list
- `App\Livewire\Teams\Create` - Team creation
- `App\Livewire\Teams\Edit` - Team editing
- `App\Livewire\Teams\Show` - Team detail with members

### Comments
- `App\Livewire\Comments\List` - Paginated comment list with delete
- `App\Livewire\Comments\Create` - Comment form with file uploads (max 5 files, 5MB each)

## Events & Listeners

### Events

| Event | Trigger |
|-------|---------|
| `ProjectCreated` | New project created |
| `TaskAssigned` | Task assigned to user |
| `TaskUpdated` | Task status/priority updated |
| `CommentAdded` | Comment posted on model |

### Listeners

| Listener | Events | Action |
|----------|--------|--------|
| `LogActivity` | ProjectCreated, TaskAssigned, TaskUpdated, CommentAdded | Creates activity record |
| `SendProjectNotification` | ProjectCreated | Notifies admins via database notification |
| `SendTaskAssignmentNotification` | TaskAssigned | Notifies assigned user via database notification |

## Real-time Features

- **Reverb** configured for WebSocket broadcasting
- Events use `Dispatchable` and `SerializesModels` traits
- Channel configuration in `routes/channels.php`
- Frontend listens for events via Livewire

## Routes

### Web Routes (`routes/web.php`)

| Method | URI | Name | Component |
|--------|-----|------|-----------|
| GET | `/` | - | Welcome page |
| GET | `/dashboard` | dashboard | Dashboard |
| GET | `/projects` | projects.index | Projects\Index |
| GET | `/projects/create` | projects.create | Projects\Create |
| GET | `/projects/{project}/edit` | projects.edit | Projects\Edit |
| GET | `/projects/{project}` | projects.show | Projects\Show |
| GET | `/tasks` | tasks.index | Tasks\Index |
| GET | `/tasks/create` | tasks.create | Tasks\Create |
| GET | `/tasks/{task}/edit` | tasks.edit | Tasks\Edit |
| GET | `/tasks/{task}` | tasks.show | Tasks\Show |
| GET | `/teams` | teams.index | Teams\Index |
| GET | `/teams/create` | teams.create | Teams\Create |
| GET | `/teams/{team}/edit` | teams.edit | Teams\Edit |
| GET | `/teams/{team}` | teams.show | Teams\Show |
| GET | `/profile` | profile | Profile view |

All routes except `/` and `/profile` require authentication and email verification.

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ProjectTest
```

Test configuration in `phpunit.xml`. Uses Laravel's built-in testing utilities.

## Deployment

### Environment Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up Redis for cache/queues
- [ ] Configure mail driver (SMTP/Sendgrid/Mailgun)
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up queue worker: `php artisan queue:work`
- [ ] Configure Reverb server for production

### Docker Support

Docker configuration available for containerized deployment with Nginx, PHP-FPM, and MySQL.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Run tests: `composer test`
4. Submit a pull request

## License

MIT License. See LICENSE file for details.
