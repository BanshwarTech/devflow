<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Edit extends Component
{
    public Project $project;

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

    public function mount(Project $project)
    {
        $this->authorize('update', $project);

        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->status = $project->status;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
    }

    public function update()
    {
        $this->authorize('update', $this->project);

        $this->validate();

        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->dispatch('project-updated', $this->project->id);

        return $this->redirectRoute('projects.show', $this->project, navigate: true);
    }

    public function render()
    {
        return view('livewire.projects.edit');
    }
}
