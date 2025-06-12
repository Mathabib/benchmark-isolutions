<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == 'admin'){
            $projects_sidebar = Project::all();
        } else{
            $projects_sidebar = Auth::user()->projects;
        }
        return view('dashboard', compact('projects_sidebar'));
    }




public function show(Project $project)
{
    // Ambil semua task project
    $tasks = $project->tasks()->get();
    
    return view('projects.show', compact('project', 'tasks'));
}

public function list(Project $project)
{
    $tasks = $project->tasks()->get();
    return view('projects.list', compact('project', 'tasks'));
}

public function index2()
{
    $projects = Project::all();
    return view('projects.index', compact('projects'));
}

public function create()
{
    return view('projects.create');
}


    // Simpan data project baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Project::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? '',
        ]);

        return redirect()->route('projects.index2') // sesuaikan dengan route index-mu
                         ->with('success', 'Project berhasil ditambahkan!');
    }


      // Tampilkan form edit project
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update data project di database
    public function update(Request $request, Project $project)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $project->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? '',
        ]);

        return redirect()->route('projects.index2') // sesuaikan dengan route index-mu
                         ->with('success', 'Project berhasil diperbarui!');
    }

    // Hapus project (kalau perlu)
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index2')
                         ->with('success', 'Project berhasil dihapus!');
    }

    public function gantt(Project $project)
    {
        $tasks = $project->tasks()
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();

        $ganttTasks = $tasks->map(function($task) {
            return [
                'id' => (string) $task->id,
                'name' => $task->nama_task,
                'start' => date('Y-m-d', strtotime($task->start_date)), 
                'end' => date('Y-m-d', strtotime($task->end_date)),
                'progress' => match ($task->status) {
                    'todo' => 0,
                    'inprogress' => 50,
                    'done' => 100,
                    default => 0
                },
                'custom_class' => 'ganti_warna',
                'color' => '#ffc909'
            ];
        });

        return view('projects.gantt', compact('project', 'ganttTasks'));
    }


public function updateTaskDates(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|integer|exists:tasks,id',
        'start' => 'required|date',
        'end' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $validator->errors(),
        ], 422);
    }

    $task = Task::find($request->id);
    $task->start_date = $request->start;
    $task->end_date = $request->end;
    $task->save();

    return response()->json([
        'message' => 'Task berhasil diperbarui.',
        'task' => $task,
    ]);
}



}
