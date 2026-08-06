# DevFlow Routes Reference

## Web Routes

All routes are defined in `routes/web.php` and protected by `auth` and `verified` middleware.

### Public Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | - | Welcome/Landing page |

### Authenticated Routes

#### Dashboard

| Method | URI | Name | Middleware | Component |
|--------|-----|------|-----------|-----------|
| GET | `/dashboard` | dashboard | auth, verified | `App\Livewire\Dashboard` |

#### Projects

| Method | URI | Name | Middleware | Component | Policy |
|--------|-----|------|-----------|-----------|--------|
| GET | `/projects` | projects.index | auth, verified | `Projects\Index` | viewAny |
| GET | `/projects/create` | projects.create | auth, verified | `Projects\Create` | create |
| GET | `/projects/{project}/edit` | projects.edit | auth, verified | `Projects\Edit` | update |
| GET | `/projects/{project}` | projects.show | auth, verified | `Projects\Show` | view |

#### Tasks

| Method | URI | Name | Middleware | Component | Policy |
|--------|-----|------|-----------|-----------|--------|
| GET | `/tasks` | tasks.index | auth, verified | `Tasks\Index` | viewAny |
| GET | `/tasks/create` | tasks.create | auth, verified | `Tasks\Create` | create |
| GET | `/tasks/{task}/edit` | tasks.edit | auth, verified | `Tasks\Edit` | update |
| GET | `/tasks/{task}` | tasks.show | auth, verified | `Tasks\Show` | view |

#### Teams

| Method | URI | Name | Middleware | Component | Policy |
|--------|-----|------|-----------|-----------|--------|
| GET | `/teams` | teams.index | auth, verified | `Teams\Index` | viewAny |
| GET | `/teams/create` | teams.create | auth, verified | `Teams\Create` | create |
| GET | `/teams/{team}/edit` | teams.edit | auth, verified | `Teams\Edit` | update |
| GET | `/teams/{team}` | teams.show | auth, verified | `Teams\Show` | view |

#### Profile

| Method | URI | Name | Middleware | Component |
|--------|-----|------|-----------|-----------|
| GET | `/profile` | profile | auth | Profile view |

### Authentication Routes

Included via `routes/auth.php` (Breeze):

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/login` | login | Login form |
| POST | `/login` | - | Login submission |
| POST | `/logout` | logout | Logout |
| GET | `/register` | register | Registration form |
| POST | `/register` | - | Registration submission |
| GET | `/forgot-password` | password.request | Forgot password form |
| POST | `/forgot-password` | password.email | Send reset link |
| GET | `/reset-password/{token}` | password.reset | Reset password form |
| POST | `/reset-password` | password.update | Reset password submission |
| GET | `/verify-email` | verification.notice | Email verification notice |
| GET | `/verify-email/{id}/{hash}` | verification.verify | Verify email |
| POST | `/email/verification-notification` | verification.send | Resend verification |

## Middleware Stack

### Global Middleware
- `VerifyCsrfToken` - CSRF protection
- `TrimStrings` - Trim input strings
- `ConvertEmptyStringsToNull` - Normalize empty strings
- `TrustProxies` - Trust proxy headers

### Route Middleware
- `auth` - Require authentication
- `verified` - Require email verification
- `role` - Require specific role (Spatie)
- `permission` - Require specific permission (Spatie)
- `role_or_permission` - Require role or permission (Spatie)

## Authorization in Routes

Policies are auto-resolved for Livewire components:
```php
// In component boot() method
$this->authorize('viewAny', Project::class);
$this->authorize('create', Project::class);
$this->authorize('update', $project);
$this->authorize('delete', $project);
```

## Parameter Binding

Livewire components use implicit model binding:
```php
public Project $project; // Automatically resolved from route
```

## Route Model Binding

Projects, Tasks, and Teams use implicit binding with 404 on missing models.
