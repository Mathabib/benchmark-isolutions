<h2 class="text-xl font-bold mb-2">{{ $task->nama_task }}</h2>

<div class="mb-2">
    <strong>Status:</strong> {{ ucfirst($task->status) }}<br>
    <strong>Priority:</strong> {{ ucfirst($task->priority) }}<br>
    <strong>Assigned To:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}<br>
    <strong>Estimate:</strong> {{ $task->estimate ?? '-' }}
</div>

@if($task->comment)
    <p class="mb-4"><strong>Description:</strong> {{ $task->comment }}</p>
@endif

<!-- Comments -->
<div class="mt-4">
    <h3 class="font-semibold mb-2">Comments:</h3>
    @foreach ($task->comments as $comment)
        <div class="bg-gray-100 p-2 rounded mb-2">
            <strong>{{ $comment->user->name }}:</strong><br>
            {{ $comment->content }}
        </div>
    @endforeach
</div>
