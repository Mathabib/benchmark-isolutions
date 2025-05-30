<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'nama_task' => 'required|string|max:255',
            'status' => 'required|in:todo,inprogress,done',
        ]);

        $task = Task::create([
            'project_id' => $validated['project_id'],
            'nama_task' => $validated['nama_task'],
            'status' => $validated['status'],
        ]);

        return response()->json(['task' => $task], 201);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:todo,inprogress,done',
        ]);

        $task = Task::findOrFail($validated['task_id']);
        $task->status = $validated['status'];
        $task->save();

        return response()->json(['success' => true]);
    }
}
