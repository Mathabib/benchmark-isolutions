<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;

class CommentController extends Controller
{

      public function index(Task $task)
    {
        // $task = Task::find($id);
        // $comments =  $task->comments()->get();
        // $comments = $task->comments()->with('user')->latest()->get();
        $comments = $task->comments()->with('user')->get();
        // return response()->json($comments);
        return $comments;
    }


    public function store(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = $task->comments()->create([
            // 'user_id' => auth()->id(),
            'user_id' => $request->user_id,
            'content' => $request->comment,
        ]);

        $comment->load('user');

        return response()->json($comment);
    }


}
