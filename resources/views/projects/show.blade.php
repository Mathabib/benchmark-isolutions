@extends('layouts.app')

@section('content')
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    /* background-color: #f4f7fa; */
    /* color: #333; */
  }

  .kanban-board {
    display: flex;
    align-items: flex-start;
    flex-direction: row;
    overflow-x: auto;
    padding: 20px;
    gap: 20px;
  }

  .kanban-column {
    background-color: #343a40;
    border-radius: 12px;
    padding: 20px;
    width: 420px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 16px rgba(0,0,0,0.05);
  }

  .column-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    font-size: 1.1rem;
    padding-bottom: 8px;
    margin-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
    /* color: #2d3748; */
  }

  .status-badge {
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 12px;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
  }

  .status-badge.todo {
    background-color: #a0aec0;
  }

  .status-badge.inprogress {
    background-color: #3182ce;
  }

  .status-badge.done {
    background-color: #38a169;
  }

  .kanban-card {
    /* background-color: #ffffff; */
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    cursor: grab;
    user-select: none;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .kanban-card-icons {
    display: flex;
    gap: 6px;
    opacity: 0.6;
  }

  .kanban-card-icons i {
    font-size: 1rem;
  }

  .kanban-card:hover {
    background-color: #f1f5f9;
    color: black;
  }

  .kanban-card.dragging {
    opacity: 0.5;
    background-color: #d1fae5;
    box-shadow: 0 0 0 2px #4caf50 inset;
  }

  .add-task-form {
    display: flex;
    margin-bottom: 10px;
    gap: 8px;
  }

  .add-task-input {
    flex-grow: 1;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e0;
    font-size: 0.95rem;
  }

  .add-task-input:focus {
    outline: none;
    /* border-color: #38a169; */
    /* background-color: #f0fff4; */
  }

  .btn-submit, .btn-cancel {
    background-color: #38a169;
    border: none;
    color: white;
    font-weight: 600;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.85rem;
  }

  .btn-submit:hover {
    background-color: #2f855a;
  }

  .btn-cancel {
    background-color: #e53e3e;
  }

  .btn-cancel:hover {
    background-color: #9b2c2c;
  }

  .add-task-btn {
    background: none;
    border: none;
    color: #4a5568;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: left;
    margin-top: 8px;
    padding: 0;
    cursor: pointer;
  }

  .add-task-btn:hover {
    text-decoration: underline;
    color: #dee2e6;
  }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<meta name="csrf-token" content="{{ csrf_token() }}">
<div>
  @include('komponen.navbar_mode')
</div>
<div style="padding: 20px 20px 0 20px;" id="project-title" data-projectid="{{ $project->id }}">
  <h1 style="font-size: 1.75rem; font-weight: 700; color:rgb(255, 255, 255);">
    {{ $project->nama ?? 'Unnamed Project' }}
  </h1>
</div>

<div class="kanban-board">
  @foreach (['todo' => 'To Do', 'inprogress' => 'In Progress', 'done' => 'Complete'] as $status => $title)
    <div class="kanban-column" id="{{ $status }}">
      <h2 class="column-header">
        <span>{{ $title }}</span>
        <span class="status-badge {{ $status }}">
          {{ $tasks->where('status', $status)->count() }}
        </span>
      </h2>

@foreach ($tasks->where('status', $status) as $task)
  <div class="kanban-card" draggable="true" data-id="{{ $task->id }}" onclick="goToTaskDetail({{ $task->id }})">
    <span>{{ $task->nama_task }}</span>
    <span class="kanban-card-icons">
    </span>
  </div>
@endforeach



      <div class="add-task-form-container"></div>

      <button class="add-task-btn" onclick="showAddTaskForm('{{ $status }}')">+ Add Task</button>
    </div>
  @endforeach
</div>

@endsection

@push('js')
<script>
  function goToTaskDetail(taskId) {
    window.location.href = '/tasks/' + taskId;
  }
</script>


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

 
  function updateTaskCounts() {
    document.querySelectorAll('.kanban-column').forEach(column => {
      const count = column.querySelectorAll('.kanban-card').length;
      const badge = column.querySelector('.status-badge');
      if (badge) {
        badge.textContent = count;
      }
    });
  }


  function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.kanban-card:not(.dragging)')];

    return draggableElements.reduce((closest, child) => {
      const box = child.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;
      if (offset < 0 && offset > closest.offset) {
        return { offset: offset, element: child }
      } else {
        return closest;
      }
    }, { offset: Number.NEGATIVE_INFINITY }).element || null;
  }


  document.querySelectorAll('.kanban-card').forEach(card => attachDragEvents(card));

  document.querySelectorAll('.kanban-column').forEach(column => {
    column.addEventListener('dragover', e => {
      e.preventDefault();

      const dragging = draggingCard;
      if (!dragging) return;

      const afterElement = getDragAfterElement(column, e.clientY);

      if (afterElement == null) {
        column.insertBefore(dragging, column.querySelector('.add-task-form-container'));
      } else {
        column.insertBefore(dragging, afterElement);
      }
    });

    column.addEventListener('drop', function () {
      if (draggingCard) {
        const taskId = draggingCard.getAttribute('data-id');
        const newStatus = column.id;

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
        .then(data => {
          console.log('Status updated', data);
          updateTaskCounts();
        })
        .catch(err => console.error('Status update error', err));
      }
    });
  });

  function showAddTaskForm(columnId) {
    const column = document.getElementById(columnId);
    const formContainer = column.querySelector('.add-task-form-container');

    if (formContainer.innerHTML.trim() !== '') return;

    formContainer.innerHTML = `
      <div class="add-task-form">
        <input type="text" class="add-task-input" placeholder="Enter new task..." />
        <button class="btn-submit" type="button">Add</button>
        <button class="btn-cancel" type="button">Cancel</button>
      </div>
    `;

    const input = formContainer.querySelector('.add-task-input');
    const submitBtn = formContainer.querySelector('.btn-submit');
    const cancelBtn = formContainer.querySelector('.btn-cancel');

    input.focus();

    submitBtn.addEventListener('click', () => {
      let elemen = document.getElementById('project-title')
      let project_id = elemen.dataset.projectid
      const taskText = input.value.trim();
      if (!taskText) return alert('Please enter task description');

      fetch('/api/tasks', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          project_id: project_id,
          nama_task: taskText,
          status: columnId
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.task) {
          const newCard = document.createElement('div');
          newCard.className = 'kanban-card';
          newCard.setAttribute('draggable', 'true');
          newCard.setAttribute('data-id', data.task.id);
          newCard.setAttribute('onclick', `goToTaskDetail(${data.task.id})`);
          newCard.innerHTML = `
            <span>${data.task.nama_task}</span>
            <span class="kanban-card-icons">
            </span>
          `;
          column.insertBefore(newCard, formContainer);
          attachDragEvents(newCard);
          formContainer.innerHTML = '';
          updateTaskCounts();
        } else {
          alert('Failed to add task');
        }
      })
      .catch(err => {
        console.error('Add task error:', err);
        alert('Error adding task');
      });
    });

    cancelBtn.addEventListener('click', () => {
      formContainer.innerHTML = '';
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        submitBtn.click();
      } else if (e.key === 'Escape') {
        cancelBtn.click();
      }
    });
  }


  updateTaskCounts();
</script>

@endpush
