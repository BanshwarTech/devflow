# DevFlow Database Schema

## Entity Relationship Overview

```
User (1) ──< (N) Project
User (1) ──< (N) Team (as owner)
User (1) ──< (N) Task (as created_by)
User (1) ──< (N) Task (as assigned_to)
User (1) ──< (N) Comment
User (1) ──< (N) Attachment
User (1) ──< (N) Activity (as causer)

Project (1) ──< (N) Task
Project (1) ──< (N) Activity (as subject)

Team (1) ──< (N) TeamMember
Team (1) ──< (N) Activity (as subject)

Task (1) ──< (N) Comment (as commentable)
Task (1) ──< (N) Activity (as subject)

Comment (1) ──< (N) Attachment (as attachable)
```

## Core Tables

### users
Laravel's default authentication table.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(255) | User display name |
| email | varchar(255) | Unique, used for login |
| email_verified_at | timestamp | Nullable |
| password | varchar(255) | Hashed |
| remember_token | varchar(100) | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Soft deletes (if enabled) |

**Indexes:**
- `email` (unique)

---

### projects
Project master data.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Required |
| description | text | Nullable |
| status | enum | planning, active, on_hold, completed, cancelled |
| start_date | date | Nullable |
| end_date | date | Nullable |
| user_id | bigint | Foreign key → users.id (owner) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `user_id` (foreign)
- Composite: `status`, `user_id`, `created_at`

---

### tasks
Task records linked to projects.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| title | varchar(255) | Required |
| description | text | Nullable |
| status | enum | pending, in_progress, review, completed, blocked |
| priority | enum | low, medium, high, urgent |
| due_date | date | Nullable |
| project_id | bigint | Foreign key → projects.id |
| assigned_to | bigint | Foreign key → users.id (nullable) |
| created_by | bigint | Foreign key → users.id |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `project_id` (foreign)
- `assigned_to` (foreign)
- `created_by` (foreign)

---

### teams
Team master data.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Required |
| description | text | Nullable |
| owner_id | bigint | Foreign key → users.id |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `owner_id` (foreign)

---

### team_members
Pivot table for team membership.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| team_id | bigint | Foreign key → teams.id |
| user_id | bigint | Foreign key → users.id |
| role | varchar(50) | Nullable (e.g., member, lead) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- Composite unique: `team_id`, `user_id`
- `team_id` (foreign)
- `user_id` (foreign)

---

## Polymorphic Tables

### comments
Polymorphic comments on projects and tasks.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| body | text | Required |
| commentable_id | bigint | Foreign key (polymorphic) |
| commentable_type | varchar(255) | Model class (polymorphic) |
| user_id | bigint | Foreign key → users.id |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `commentable_id`, `commentable_type` (composite, via morphs)

---

### attachments
Polymorphic file attachments on comments.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Original filename |
| path | varchar(255) | Storage path |
| mime_type | varchar(255) | File MIME type |
| size | int | File size in bytes |
| attachable_id | bigint | Foreign key (polymorphic) |
| attachable_type | varchar(255) | Model class (polymorphic) |
| user_id | bigint | Foreign key → users.id |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `attachable_id`, `attachable_type` (composite, via morphs)

---

### activities
Activity log for tracking user actions.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| description | varchar(255) | Action description |
| subject_id | bigint | Foreign key (polymorphic) |
| subject_type | varchar(255) | Model class (polymorphic) |
| causer_id | bigint | Foreign key → users.id (nullable) |
| properties | json | Additional data (nullable) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `subject_id`, `subject_type` (composite, via morphs)
- `causer_id` (foreign)

---

### notifications
Laravel notification storage.

| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| type | varchar(255) | Notification class |
| notifiable_type | varchar(255) | Model class |
| notifiable_id | bigint | Model ID |
| data | json | Notification payload |
| read_at | timestamp | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- `notifiable_type`, `notifiable_id` (composite)

---

## Permission Tables (Spatie)

### permissions
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(125) | Permission name |
| guard_name | varchar(125) | Guard name (web) |
| created_at | timestamp | |
| updated_at | timestamp | |

### roles
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(125) | Role name |
| guard_name | varchar(125) | Guard name (web) |
| created_at | timestamp | |
| updated_at | timestamp | |

### model_has_roles
| Column | Type | Notes |
|--------|------|-------|
| role_id | bigint | Foreign key → roles.id |
| model_type | varchar(255) | Model class (User) |
| model_id | bigint | Foreign key → users.id |

**Indexes:**
- Composite unique: `role_id`, `model_id`, `model_type`

### model_has_permissions
| Column | Type | Notes |
|--------|------|-------|
| permission_id | bigint | Foreign key → permissions.id |
| model_type | varchar(255) | Model class (User) |
| model_id | bigint | Foreign key → users.id |

**Indexes:**
- Composite unique: `permission_id`, `model_id`, `model_type`

### role_has_permissions
| Column | Type | Notes |
|--------|------|-------|
| permission_id | bigint | Foreign key → permissions.id |
| role_id | bigint | Foreign key → roles.id |

**Indexes:**
- Composite unique: `permission_id`, `role_id`

---

## Feature Tables (Laravel Pennant)

### features
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | varchar(255) | Feature identifier |
| value | json | Feature state |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## Monitoring Tables (Laravel Pulse)

### pulse_entries
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| type | varchar(255) | Entry type |
| key | varchar(255) | Unique identifier |
| tags | json | Associated tags |
| content | json | Entry data |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## Default Seed Data

### Roles
- Admin - All permissions
- Manager - manage projects, tasks, teams, view dashboard
- Employee - view dashboard only

### Users
| Email | Role | Password |
|-------|------|----------|
| admin@devflow.test | Admin | password |
| manager@devflow.test | Manager | password |
| employee@devflow.test | Employee | password |

---

## Migrations

All migrations are timestamped and located in `database/migrations/`:

| Migration File | Table |
|----------------|-------|
| `0001_01_01_000000_create_users_table.php` | users |
| `0001_01_01_000001_create_cache_table.php` | cache |
| `0001_01_01_000002_create_jobs_table.php` | jobs |
| `2026_08_03_093729_create_permission_tables.php` | permissions, roles, etc. |
| `2026_08_03_093736_create_pulse_tables.php` | pulse_* |
| `2026_08_03_093745_create_features_table.php` | features |
| `2026_08_03_093803_create_personal_access_tokens_table.php` | personal_access_tokens |
| `2026_08_03_094000_create_projects_table.php` | projects |
| `2026_08_03_094001_create_tasks_table.php` | tasks |
| `2026_08_03_094002_create_teams_table.php` | teams |
| `2026_08_03_094003_create_team_members_table.php` | team_members |
| `2026_08_03_094004_create_comments_table.php` | comments |
| `2026_08_03_094005_create_attachments_table.php` | attachments |
| `2026_08_03_094006_create_activities_table.php` | activities |
| `2026_08_03_094007_create_notifications_table.php` | notifications |
