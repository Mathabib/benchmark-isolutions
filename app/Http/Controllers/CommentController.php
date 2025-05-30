<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;

class CommentController extends Controller
{

      public function index(Task $task)
    {
        $comments = $task->comments()->with('user')->latest()->get();
        return response()->json($comments);
    }


 public function store(Request $request, Task $task)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
    ]);

    $comment = $task->comments()->create([
        'user_id' => auth()->id(),
        'content' => $request->comment,
    ]);

    $comment->load('user');

    return response()->json($comment);
}


}
