<?php

use App\Livewire\Dashboard;
use App\Livewire\Projects\Create as ProjectCreate;
use App\Livewire\Projects\Edit as ProjectEdit;
use App\Livewire\Projects\Index as ProjectIndex;
use App\Livewire\Projects\Show as ProjectShow;
use App\Livewire\Tasks\Create as TaskCreate;
use App\Livewire\Tasks\Edit as TaskEdit;
use App\Livewire\Tasks\Index as TaskIndex;
use App\Livewire\Tasks\Show as TaskShow;
use App\Livewire\Teams\Create as TeamCreate;
use App\Livewire\Teams\Edit as TeamEdit;
use App\Livewire\Teams\Index as TeamIndex;
use App\Livewire\Teams\Show as TeamShow;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('projects', ProjectIndex::class)->name('projects.index');
    Route::get('projects/create', ProjectCreate::class)->name('projects.create');
    Route::get('projects/{project}/edit', ProjectEdit::class)->name('projects.edit');
    Route::get('projects/{project}', ProjectShow::class)->name('projects.show');

    Route::get('tasks', TaskIndex::class)->name('tasks.index');
    Route::get('tasks/create', TaskCreate::class)->name('tasks.create');
    Route::get('tasks/{task}/edit', TaskEdit::class)->name('tasks.edit');
    Route::get('tasks/{task}', TaskShow::class)->name('tasks.show');

    Route::get('teams', TeamIndex::class)->name('teams.index');
    Route::get('teams/create', TeamCreate::class)->name('teams.create');
    Route::get('teams/{team}/edit', TeamEdit::class)->name('teams.edit');
    Route::get('teams/{team}', TeamShow::class)->name('teams.show');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
