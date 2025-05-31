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
public function show(Task $task)
{
    $task->load('assignToUser', 'comments.user');
    // return $task;
    return view('tasks.show', compact('task'));
}


public function update(Request $request, Task $task)
{
    $request->validate([
        'nama_task' => 'required|string|max:255',
        'status' => 'required|in:todo,inprogress,done',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'estimate' => 'nullable',
        'assign_to' => 'nullable|exists:users,id',
        'priority' => 'required|in:low,medium,high',
    ]);

    $task->update([
        'nama_task' => $request->nama_task,
        'status' => $request->status,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'estimate' => $request->estimate,
        'assign_to' => $request->assign_to,
        'priority' => $request->priority,
    ]);

    return response()->json($task); // balikin data terbaru dalam json
}


}
