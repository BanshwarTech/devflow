<?php

namespace App\Livewire\Projects;

use App\Models\Activity;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public Project $project;

    public function mount(Project $project)
    {
        $this->authorize('view', $project);

        $this->project = $project;
    }

    public function delete()
    {
        $this->authorize('delete', $this->project);

        $this->project->delete();

        return $this->redirectRoute('projects.index', navigate: true);
    }

    #[On('project-updated')]
    public function refreshOnProjectUpdated(): void
    {
        $this->refresh();
    }

    public function render()
    {
        $tasks = $this->project->tasks()->latest()->get();
        $activities = Activity::query()
            ->where('subject_type', Project::class)
            ->where('subject_id', $this->project->id)
            ->latest()
            ->get();

        return view('livewire.projects.show', [
            'tasks' => $tasks,
            'activities' => $activities,
        ]);
    }
}
