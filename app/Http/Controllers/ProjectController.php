<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('dashboard', compact('projects'));
    }

  public function show(Project $project)
{
    $projects = Project::all(); // <-- Tambahkan ini
    $project->load('tasks.assignToUser', 'tasks.comments.user');
    return view('projects.show', compact('project', 'projects'));
}

}
