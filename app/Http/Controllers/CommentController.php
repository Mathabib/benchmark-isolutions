<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
public function store(Request $request, Task $task)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added!');
    }
}
