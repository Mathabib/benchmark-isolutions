<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'project_id' => 'required|exists:projects,id',
        //     'nama_task' => 'required|string|max:255',
        //     'status' => 'required|in:todo,inprogress,done',
        // ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'nama_task' => $request->nama_task,
            'status' => $request->status,
            'description' => $request->description
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
    // return $request;

    $request->validate([
        'nama_task' => 'nullable|string|max:255',
        'status' => 'nullable|in:todo,inprogress,done',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'estimate' => 'nullable',
        'assign_to' => 'nullable|exists:users,id',
        'priority' => 'nullable|in:low,medium,high',
        'description' => 'nullable'
    ]);

     $data = [];

    if ($request->filled('nama_task')) {
        $data['nama_task'] = $request->nama_task;
    }

    if ($request->filled('status')) {
        $data['status'] = $request->status;
    }

    if ($request->filled('start_date')) {
        $data['start_date'] = $request->start_date;
    }

    if ($request->filled('end_date')) {
        $data['end_date'] = $request->end_date;
    }

    if ($request->filled('estimate')) {
        $data['estimate'] = $request->estimate;
    }

    if ($request->filled('assign_to')) {
        $data['assign_to'] = $request->assign_to;
    }

    if ($request->filled('priority')) {
        $data['priority'] = $request->priority;
    }

    if ($request->filled('description')) {
        $data['description'] = $request->description;
    }

    if (!empty($data)) {
    $task->update($data);
    }

    // $task->update([
    //     'nama_task' => $request->nama_task,
    //     'status' => $request->status,
    //     'start_date' => $request->start_date,
    //     'end_date' => $request->end_date,
    //     'estimate' => $request->estimate,
    //     'assign_to' => $request->assign_to,
    //     'priority' => $request->priority,
    //     'description' => $request->description
    // ]);

    // return 'berhasil terubah';

    // return redirect()->back();

    return response()->json($task); // balikin data terbaru dalam json
}


}
