# DevFlow Architecture

## Overview

DevFlow follows Laravel 13's modular architecture with a clear separation of concerns across Models, Livewire Components, Policies, Events, and Jobs.

## Directory Structure

```
app/
├── Events/           # Domain events (dispatchable)
├── Http/             # Controllers, Middleware, Requests
├── Listeners/        # Event listeners (sync/queued)
├── Livewire/         # UI layer (Volt + Components)
│   ├── Actions/      # Shared Livewire actions
│   ├── Comments/     # Comment components
│   ├── Dashboard.php # Dashboard component
│   ├── Forms/        # Shared form components
│   ├── Projects/     # Project CRUD components
│   ├── Tasks/        # Task CRUD components
│   └── Teams/        # Team CRUD components
├── Models/           # Eloquent models
├── Policies/         # Authorization policies
├── Providers/        # Service providers
└── View/             # View composers
```

## Layers

### 1. Presentation Layer
- **Livewire Volt** - Functional components for UI interactivity
- **Blade Templates** - Server-side rendered views with Tailwind CSS
- **Alpine.js** - Client-side interactivity (via Volt)

### 2. Application Layer
- **Livewire Components** - Handle form submissions, validation, state
- **Routes** - Define HTTP endpoints
- **Middleware** - Auth, verified, role/permission checks

### 3. Domain Layer
- **Models** - Eloquent ORM with relationships
- **Events** - Domain events triggered by actions
- **Policies** - Authorization logic

### 4. Infrastructure Layer
- **Listeners** - React to events (logging, notifications)
- **Jobs/Queues** - Async processing
- **Notifications** - User notifications

## Design Patterns

### Active Record (Eloquent Models)
Models encapsulate both data and business logic.

```php
class Project extends Model {
    use HasFactory;
    public function tasks(): HasMany { ... }
}
```

### Observer Pattern (Events/Listeners)
Decoupled reaction to domain events.

```php
ProjectCreated -> SendProjectNotification + LogActivity
TaskAssigned -> SendTaskAssignmentNotification + LogActivity
```

### Policy Pattern (Authorization)
Centralized authorization rules.

```php
ProjectPolicy::view(User, Project): bool
```

### Polymorphic Relations
Comments and attachments attach to multiple models.

```php
Comment -> morphTo(commentable) -> Project, Task
Attachment -> morphTo(attachable) -> Comment
```

## Component Architecture

### State Management
- Public properties = component state
- `#[Validate]` = inline validation rules
- `#[Computed]` = lazy-loaded derived state

### Navigation
- Permission-based via `@can` directives
- Responsive with mobile hamburger menu
- Active state via `request()->routeIs()`

## Event Flow

```
User Action (Livewire Component)
    ↓
dispatch() event
    ↓
EventServiceProvider routes to Listeners
    ↓
Sync: LogActivity → Activity::create()
Queued: SendProjectNotification → User::notify()
```

## Queue Strategy

- **Database driver** for development
- **Redis driver** for production
- Listeners implement `ShouldQueue` for async processing
- Failed jobs tracked in `failed_jobs` table

## Broadcasting

- **Reverb** for WebSocket connections
- Events implement `Dispatchable` + `SerializesModels`
- Frontend subscribes via Echo + Reverb client

## Caching

- **Database cache** for development
- **Redis** recommended for production
- Route and config caching for deployment

## Security

- **Breeze** handles authentication (login, register, password reset)
- **Spatie Permission** for role-based access
- **Policies** for model-level authorization
- **CSRF protection** via Laravel middleware
- **Password hashing** via Bcrypt (12 rounds)

## Scalability Considerations

1. **Queues** - Offload notifications and heavy processing
2. **Redis** - Cache, sessions, and queue backend
3. **Database indexing** - Proper indexes on foreign keys
4. **Pagination** - All lists paginated (10 items/page)
5. **Polymorphic queries** - Optimized with eager loading
