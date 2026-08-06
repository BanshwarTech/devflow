<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string')]
    public ?string $description = null;

    #[Validate('required|in:planning,active,on_hold,completed,cancelled')]
    public string $status = 'planning';

    #[Validate('required|date|before_or_equal:end_date')]
    public ?string $start_date = null;

    #[Validate('nullable|date|after_or_equal:start_date')]
    public ?string $end_date = null;

    public function boot()
    {
        $this->authorize('create', Project::class);
    }

    public function create()
    {
        $this->validate();

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => auth()->id(),
        ]);

        return $this->redirectRoute('projects.show', $project, navigate: true);
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}
