@extends('layouts.app')

@section('content')
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #e3e4e6;
    }

    .kanban-board {
      display: flex;
      flex-direction: row;
      overflow-x: auto;
      padding: 20px;
      height: 100vh;
      box-sizing: border-box;
    }

    .kanban-column {
      background-color: #f0f2f5;
      border-radius: 8px;
      padding: 16px;
      margin-right: 20px;
      width: 300px;
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      max-height: 100%;
    }

    .kanban-column h2 {
      text-align: center;
      margin-bottom: 10px;
      color: #333;
    }

    .kanban-card {
      background-color: white;
      border-radius: 6px;
      padding: 10px;
      margin-bottom: 10px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      cursor: grab;
      transition: background-color 0.2s;
    }

    .kanban-card.dragging {
      opacity: 0.5;
    }

    .add-task-btn {
      margin-top: auto;
      background-color: #4caf50;
      color: white;
      padding: 8px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.2s;
    }

    .add-task-btn:hover {
      background-color: #45a049;
    }
  </style>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="kanban-board">
    @foreach (['todo' => 'To Do', 'inprogress' => 'In Progress', 'done' => 'Done'] as $status => $title)
      <div class="kanban-column" id="{{ $status }}">
        <h2>{{ $title }}</h2>
        @foreach ($tasks->where('status', $status) as $task)
          <div class="kanban-card" draggable="true" data-id="{{ $task->id }}">
            {{ $task->nama_task }}
          </div>
        @endforeach
        <button class="add-task-btn" onclick="addTask('{{ $status }}')">+ Add Task</button>
      </div>
    @endforeach
  </div>
@endsection

@push('js')
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  let draggingCard = null;

  function attachDragEvents(card) {
    card.addEventListener('dragstart', () => {
      draggingCard = card;
      card.classList.add('dragging');
    });

    card.addEventListener('dragend', () => {
      card.classList.remove('dragging');
      draggingCard = null;
    });
  }

  document.querySelectorAll('.kanban-card').forEach(card => attachDragEvents(card));

  document.querySelectorAll('.kanban-column').forEach(column => {
    column.addEventListener('dragover', e => e.preventDefault());

    column.addEventListener('drop', function () {
      if (draggingCard) {
        column.insertBefore(draggingCard, column.querySelector('.add-task-btn'));
        const taskId = draggingCard.getAttribute('data-id');
        const newStatus = column.id;

        if (taskId) {
          fetch('/tasks/update-status', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
              task_id: taskId,
              status: newStatus
            })
          })
          .then(res => res.json())
          .then(data => console.log('Status updated', data))
          .catch(err => console.error('Status update error', err));
        }
      }
    });
  });

  function addTask(columnId) {
    const taskText = prompt("Enter task description:");
    if (!taskText || !taskText.trim()) return;

    fetch('/tasks', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        project_id: 1, // Ganti dengan project_id yang valid
        nama_task: taskText.trim(),
        status: columnId
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.task) {
        const column = document.getElementById(columnId);
        const newCard = document.createElement('div');
        newCard.className = 'kanban-card';
        newCard.setAttribute('draggable', 'true');
        newCard.setAttribute('data-id', data.task.id);
        newCard.textContent = data.task.nama_task;

        column.insertBefore(newCard, column.querySelector('.add-task-btn'));
        attachDragEvents(newCard);
      }
    })
    .catch(err => console.error('Add task error:', err));
  }
</script>
@endpush
