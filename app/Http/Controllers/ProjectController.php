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


}
