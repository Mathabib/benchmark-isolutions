<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'nama_task' => 'required|string|max:255',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'estimate' => 'nullable',
            'assign_to' => 'nullable|exists:users,id',
            'priority' => 'required|string',
            'comment' => 'nullable|string',
        ]);
        $data['project_id'] = $project->id;

        Task::create($data);

        return back()->with('success', 'Task added!');
    }
}
