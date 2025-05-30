@extends('layouts.app')

@section('content')
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7fa;
    color: #333;
  }
.kanban-board {
  display: flex;
  align-items: flex-start;
  flex-direction: row;
  overflow-x: auto;
  padding: 20px;
  box-sizing: border-box;
  gap: 20px;
}


  .kanban-column {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 20px;
    width: 420px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
  }

  .kanban-column:hover {
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
  }

  .kanban-column h2 {
    text-align: center;
    margin-bottom: 15px;
    font-weight: 700;
    font-size: 1.5rem;
    color: #2d3748;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 8px;
  }

  .kanban-card {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 14px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    cursor: grab;
    user-select: none;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    transition: background-color 0.25s ease, box-shadow 0.25s ease;
  }
  .kanban-card:hover {
    background-color: #edf2f7;
    box-shadow: 0 6px 14px rgba(0,0,0,0.1);
  }
  .kanban-card.dragging {
    opacity: 0.5;
    box-shadow: 0 0 0 2px #4caf50 inset;
    background-color: #d1fae5;
  }

  .add-task-btn {
    background-color: #38a169;
    color: white;
    padding: 12px 0;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 0.05em;
    box-shadow: 0 5px 10px rgba(56,161,105,0.4);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    margin-top: auto;
  }
  .add-task-btn:hover {
    background-color: #2f855a;
    box-shadow: 0 8px 16px rgba(47,133,90,0.6);
  }
  .add-task-btn:active {
    background-color: #276749;
    box-shadow: none;
    transform: translateY(1px);
  }

  /* Form input */
  .add-task-form {
    display: flex;
    margin-bottom: 14px;
    gap: 8px;
  }
  .add-task-input {
    flex-grow: 1;
    padding: 10px 14px;
    border-radius: 8px;
    border: 1.5px solid #cbd5e0;
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    transition: border-color 0.25s ease;
  }
  .add-task-input:focus {
    outline: none;
    border-color: #38a169;
    background-color: #f0fff4;
  }
  .btn-submit, .btn-cancel {
    background-color: #38a169;
    border: none;
    color: white;
    font-weight: 700;
    padding: 10px 14px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
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

      <!-- Tempat form input muncul -->
      <div class="add-task-form-container"></div>

      <!-- Tombol Add Task tetap di bawah -->
      <button class="add-task-btn" onclick="showAddTaskForm('{{ $status }}')">+ Add Task</button>
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

  function showAddTaskForm(columnId) {
    const column = document.getElementById(columnId);
    const formContainer = column.querySelector('.add-task-form-container');

    // Jika form sudah ada, jangan buat lagi
    if (formContainer.innerHTML.trim() !== '') return;

    // Buat elemen form
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

    // Fokus input
    input.focus();

    submitBtn.addEventListener('click', () => {
      const taskText = input.value.trim();
      if (!taskText) return alert('Please enter task description');

      fetch('/tasks', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          project_id: 1, 
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
          newCard.textContent = data.task.nama_task;

          // Masukkan sebelum formContainer (biar di bawah task terakhir)
          column.insertBefore(newCard, formContainer);

          attachDragEvents(newCard);

          // Hapus form setelah submit
          formContainer.innerHTML = '';
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
</script>
@endpush
