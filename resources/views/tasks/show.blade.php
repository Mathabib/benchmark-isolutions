@extends('layouts.app')

@section('content')
<style>
  .cell-label{
    width: 150px;
    font-weight: bold;
  }
  .cell-konten{
    width: 200px;
  }
</style>
<div style="display:flex; gap:20px;">

  <!-- Form Edit Task -->
  <div style="flex:2;  padding:20px; border-radius:12px;">
    <h2>Edit Task</h2>
    <div id="success-message" style="color:green; margin-bottom: 15px; display:none;"></div>

    <form id="task-form">
      @csrf
      @method('PUT')

      <div class="d-flex p-5 justify-content-between">
        <div role="table">
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Status</div> 
            <div role="cell" class=" cell-konten">
              <select class="form-control form-control-sm" name="status" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                @foreach(['todo' => 'To Do', 'inprogress' => 'In Progress', 'done' => 'Complete'] as $key => $label)
                  <option value="{{ $key }}" @if(old('status', $task->status) == $key) selected @endif>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Priority</div> 
            <div role="cell" class=" cell-konten">
              <select class="form-control form-control-sm" name="priority" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $key => $label)
                  <option value="{{ $key }}" @if(old('priority', $task->priority) == $key) selected @endif>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>          
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Assign To</div> 
            <div role="cell" class=" cell-konten">
              <select class="form-control form-control-sm" name="assign_to" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                <option value="">-- None --</option>
                @foreach(App\Models\User::all() as $user)
                  <option value="{{ $user->id }}" @if(old('assign_to', $task->assign_to) == $user->id) selected @endif>{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>          
        </div>

        <div role="table">
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Date Start</div> 
            <div role="cell" class=" cell-konten">
              <input class="form-control form-control-sm" type="date" name="start_date" value="{{ old('start_date', optional($task->start_date)->format('Y-m-d')) }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Deadline</div> 
            <div role="cell" class=" cell-konten">
              <input class="form-control form-control-sm" type="date" name="end_date" value="{{ old('end_date', optional($task->end_date)->format('Y-m-d')) }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Time Estimate</div> 
            <div role="cell" class=" cell-konten">
              <input class="form-control form-control-sm" type="time" name="estimate" value="{{ old('estimate', $task->estimate) }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            </div>
          </div>
        </div>
      </div>


      <div class="mb-3">
        <label for="description" name="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" rows="10"></textarea>
      </div>


      <br>
      <button type="submit" style="background:#38a169; color:#fff; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">Save</button>
    </form>
  </div>

  <!-- Comments Section -->
  <div class="border border-white" style="flex:1; padding:20px; border-radius:12px; box-shadow:0 8px 16px rgba(0,0,0,0.05); max-height: 600px; overflow-y: auto;">
    <h3>Comments</h3>
    <div id="comments-container" data-url="{{ route('get.comments', ['task' => $task->id]) }}" style="display:flex; flex-direction: column-reverse; gap: 12px;">
      {{-- @foreach($task->comments->sortByDesc('created_at') as $comment)
        <div style="background:#fff; padding:10px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
          <strong>{{ $comment->user->name }}</strong> 
          <small style="color:#555;">{{ $comment->created_at->format('d M Y, H:i') }}</small>
          <p>{{ $comment->content }}</p>
        </div>
      @endforeach --}}
    </div>

    <form id="comment-form" style="margin-top: 20px;">
      @csrf
      <textarea class="form-control" id="comment-input" rows="3" placeholder="Add a comment..." style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"></textarea>
      <button type="submit" style="margin-top: 10px; background:#3182ce; color:#fff; padding:10px 15px; border:none; border-radius:8px; cursor:pointer;">Send</button>
    </form>
  </div>

</div>

<script src="{{ asset('js/jquery.js') }}"></script>
<script>
  const url = document.getElementById('comments-container').dataset.url;
  function loadComments() {
    $.get(`${url}`, function(response) {
      const container = $('#comments-container');
      container.empty(); // kosongkan dulu
      console.log(response);
      response.forEach(comment => {
        const html = `
          <div style="padding:10px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <small>${new Date(comment.created_at).toLocaleString()}</small>
            <p>${comment.content}</p>
          </div>
        `;
        container.append(html);
      });
    });
  }

  // Contoh: Panggil saat halaman load (misal task ID = 1)
  $(document).ready(function() {
    loadComments();
  });

</script>


{{-- <script>
  const taskId = {{ $task->id }};
  const commentsContainer = document.getElementById('comments-container');
  const commentForm = document.getElementById('comment-form');
  const commentInput = document.getElementById('comment-input');
  const taskForm = document.getElementById('task-form');
  const successMessage = document.getElementById('success-message');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  taskForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(taskForm);
    const data = {};
    formData.forEach((value, key) => data[key] = value);

    fetch(`/tasks/${taskId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify(data)
    })
    .then(res => {
      if(!res.ok) throw new Error('Failed to update task');
      return res.json();
    })
    .then(updatedTask => {
      successMessage.textContent = 'Task updated successfully!';
      successMessage.style.display = 'block';

      for (const key in updatedTask) {
        if (taskForm.elements[key]) {
          if (key === 'start_date' || key === 'end_date') {
            taskForm.elements[key].value = updatedTask[key] ? updatedTask[key].substring(0,10) : '';
          } else {
            taskForm.elements[key].value = updatedTask[key] ?? '';
          }
        }
      }
    })
    .catch(err => {
      alert(err.message);
    });
  });

  commentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const comment = commentInput.value.trim();
    if (!comment) return alert('Comment cannot be empty');

    fetch(`/tasks/${taskId}/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ comment })
    })
    .then(res => {
      if (!res.ok) throw new Error('Failed to send comment');
      return res.json();
    })
    .then(data => {
      commentInput.value = '';

      // Tambahkan komentar baru ke container
      const newComment = document.createElement('div');
      newComment.style.background = '#fff';
      newComment.style.padding = '10px';
      newComment.style.borderRadius = '8px';
      newComment.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
      newComment.innerHTML = `
        <strong>${data.user.name}</strong> 
        <small style="color:#555;">${new Date(data.created_at).toLocaleString()}</small>
        <p>${data.content}</p>
      `;
      commentsContainer.prepend(newComment);
    })
    .catch(() => alert('Failed to send comment'));
  });
</script> --}}
@endsection
