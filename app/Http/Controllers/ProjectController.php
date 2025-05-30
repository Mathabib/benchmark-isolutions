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
    // Ambil semua task project
    $tasks = $project->tasks()->get();

    return view('projects.show', compact('project', 'tasks'));
}


}
