# DevFlow Testing Guide

## Overview

DevFlow uses PHPUnit for testing. All tests are located in the `tests/` directory.

## Test Structure

```
tests/
├── Feature/          # Feature tests (HTTP layer)
├── Unit/             # Unit tests (business logic)
└── TestCase.php      # Base test case
```

## Running Tests

```bash
# Run all tests
composer test

# Run specific test file
php artisan test --filter=ProjectTest

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter=test_user_can_create_project
```

## Test Configuration

`phpunit.xml` configuration:
- In-memory SQLite database for test isolation
- Refresh database between tests
- Seed database with test data

## Writing Tests

### Feature Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project(): void
    {
        $user = User::factory()->admin()->create();
        
        $response = $this->actingAs($user)->post('/projects', [
            'name' => 'Test Project',
            'status' => 'planning',
        ]);
        
        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', ['name' => 'Test Project']);
    }
}
```

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Project;
use App\Models\Task;

class ProjectTest extends TestCase
{
    public function test_project_has_many_tasks(): void
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        
        $this->assertTrue($project->tasks->contains($task));
    }
}
```

## Factories

Factories are located in `database/factories/`:

| Factory | Model | Key Fields |
|---------|-------|------------|
| UserFactory | User | name, email, password |
| ProjectFactory | Project | name, status, user_id |
| TaskFactory | Task | title, status, priority, project_id |
| TeamFactory | Team | name, owner_id |
| TeamMemberFactory | TeamMember | team_id, user_id |
| CommentFactory | Comment | body, commentable_id, user_id |
| AttachmentFactory | Attachment | name, path, attachable_id |
| ActivityFactory | Activity | description, subject_id, causer_id |

## Test Helpers

- `RefreshDatabase` - Migrate database before each test
- `WithFaker` - Generate fake data
- `actingAs()` - Authenticate user for request

## Continuous Integration

Tests should pass before merging. Run locally:
```bash
composer test
```
