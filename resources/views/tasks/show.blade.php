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
  [contenteditable="true"] {
  border: none;
  outline: none;
  }
</style>
<div style="display:flex; gap:20px;">

  <!-- Form Edit Task -->
  <div style="flex:2;  padding:20px; border-radius:12px;">
    <div class="d-flex justify-content-between">
      <div class="">
        <a href="{{ url()->previous() }}" style="text-decoration: none;color: white"><h2><i class="fa-solid fa-arrow-left"></i></h2></a>
      </div>
      <div class="dropdown">        
        <span class="float-end fs-5 fw-bold" style="cursor: pointer"  data-bs-toggle="dropdown" aria-expanded="false"><h1>...</h1></span>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('task.delete', ['project' => $task->project->id, 'task' => $task->id]) }}">Delete Task</a></li>
        </ul>
      </div>
    </div>
    <div class="row">
      
      <div class="col-10">
        <h3 contenteditable="true" id="nama_task_editable">{{ $task->nama_task }}</h3>
        <input type="hidden" name="nama_task" id="nama_task_input">
      </div>      
    </div>
    
    <div id="success-message" style="color:green; margin-bottom: 15px; display:none;"></div>

    <form id="task-form" data-url="{{ route('tasks.update.api', ['task' => $task->id]) }}">
      @csrf
      {{-- @method('PUT') --}}
      @method('POST')

      <div class="d-flex p-5 justify-content-between">
        <div role="table">
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Status</div> 
            <div role="cell" class=" cell-konten">
              <select {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control form-control-sm" name="status" id="status_input" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                @foreach(['todo' => 'To Do', 'inprogress' => 'In Progress', 'done' => 'Complete'] as $key => $label)
                  <option value="{{ $key }}" @if(old('status', $task->status) == $key) selected @endif>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Priority</div> 
            <div role="cell" class=" cell-konten">
              <select {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control form-control-sm" name="priority" id="priority_input" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $key => $label)
                  <option value="{{ $key }}" @if(old('priority', $task->priority) == $key) selected @endif>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>          
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Assign To</div> 
            <div role="cell" class=" cell-konten">
              <select {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control form-control-sm" name="assign_to" id="assign_to_input" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
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
              <input {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control form-control-sm" type="date" name="start_date" id="start_date_input"  value="{{ old('start_date', $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : '') }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Deadline</div> 
            <div role="cell" class=" cell-konten">
              <input {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control form-control-sm" type="date" name="end_date" id="end_date_input" value="{{ old('end_date', $task->end_date ? \Carbon\Carbon::parse($task->end_date)->format('Y-m-d') : '' ) }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            </div>
          </div>
          <div role="row" class="d-flex mb-3">
            <div role="cell" class="me-3 cell-label" >Time Estimate</div> 
            <div role="cell" class=" cell-konten" id="taskid" data-taskid="{{ $task->id }}" data-estimateurl="{{ route('tasks.estimate.api', ['task' => $task->id]) }}" data-estimate_update_url="{{ route('tasks.update.api', ['task' => $task->id]) }}">
              <span id="estimate_content">{{ $estimate }} Days</span>
            </div>
          </div>
        </div>
      </div>
    @if(Auth::user()->role == 'admin')      
    <div class="mb-4">
      <label for="attachment" class="form-label fw-bold">Upload Attachment</label>
      <input type="file" name="attachment[]" id="attachment" class="form-control" multiple>
      <small class="form-text text-muted">You can select multiple files at once.</small>
    </div>
    @endif

    <div id="attachment-section" class="mb-4 {{ $task->attachments && $task->attachments->count() > 0 ? '' : 'd-none' }}">
      <label class="form-label fw-bold">Existing Attachments</label>
      <ul class="list-group" id="attachment-list">
        @if($task->attachments && $task->attachments->count() > 0)
          @foreach($task->attachments as $file)
            <li class="list-group-item d-flex justify-content-between align-items-center" id="attachment-item-{{ $file->id }}">
              <div class="d-flex align-items-center">
                <i class="bi bi-paperclip me-2 text-primary"></i>
                <a href="{{ asset('storage/attachments/' . $file->filename) }}" target="_blank" class="text-decoration-none fw-medium">
                  {{ $file->filename }}
                </a>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary">{{ strtoupper(pathinfo($file->filename, PATHINFO_EXTENSION)) }}</span>
                <button type="button" class="btn btn-sm btn-outline-danger delete-attachment" data-id="{{ $file->id }}">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </li>
          @endforeach
        @endif
      </ul>
    </div>




      <div class="mb-3">
        <label for="description" name="description" class="form-label">Description</label>
        <textarea {{ Auth::user()->role == 'user' ? 'disabled' : ''}} class="form-control" id="description" rows="10">{{ old('description', $task->description) }}</textarea>
      </div>



    </form>
  </div>

  <!-- Comments Section -->
  <div class="border border-white" style="flex:1; padding:20px; border-radius:12px; box-shadow:0 8px 16px rgba(0,0,0,0.05); max-height: 600px;">
    <h3>Comments</h3>
    <div id="comments-container" style="height: 350px; overflow-y: auto" data-url="{{ route('get.comments', ['task' => $task->id]) }}" data-userid="{{ Auth::user()->id }}" style="display:flex; flex-direction: column-reverse; gap: 12px;">
      
    </div>

    <div class="form-isi" id="comment-form" style="margin-top: 20px; position: relative;">
      <textarea class="form-control" id="comment-input" rows="3" placeholder="Add a comment..." style="width:100%; padding:10px; border-radius:8px; border:1px solid #ccc;"></textarea>
      <button id="kirim_comment" style="margin-top: 10px; background:#3182ce; color:#fff; padding:10px 15px; border:none; border-radius:8px; cursor:pointer;">Send</button>
    </div>
    
  </div>

</div>

<script src="{{ asset('js/jquery.js') }}"></script>

<script>
  $(document).ready(function() {
    var url = $('#comments-container').data('url'); // ambil data-url dengan jQuery

    function loadComments() {
      $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          var container = $('#comments-container');
          container.empty(); // kosongkan dulu

          console.log(response);

          $.each(response, function(index, comment) {
            var html = `
              <div style="padding:10px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <small><strong>${comment.user.name}</strong></small>
                <small>${new Date(comment.created_at).toLocaleString()}</small>
                <p>${comment.content}</p>
              </div>
            `;
            container.append(html);
          });
        },
        error: function(xhr, status, error) {
          console.error('Gagal mengambil komentar:', error);
        }
      });
    }

    function kirimKomentar() {
      let userID = $('#comments-container').data('userid')
      let url = $('#comments-container').data('url')
      let comment = $('#comment-input').val()
      $.ajax({
        url: url,
        method: 'POST',
        data: {
          user_id: userID,
          comment: comment
        },
        success: function(response) {
          console.log('Komentar berhasil ditambahkan:', response);
          $('#comment-input').val('');
          loadComments();
        },
        error: function(xhr) {
          console.error('Gagal menambahkan komentar:', xhr.responseJSON);
        }
      });

    }

    $('#kirim_comment').on('click', function() {
      console.log('tombol kirim bekerja dengan baik alhamdulillah')
      comment = $('#comment-input').val();
      let userID = $('#comments-container').data('userid')
        if (comment.trim()) {
          kirimKomentar()
          console.log(userID)
        } else {
          alert('gak ada isi');
        }

    })

//BAGIAN UNTUK FORM 
    nama_task_editable = $('#nama_task_editable');
    nama_task_input = $('#nama_task_input');
    status_input = $('#status_input');
    priority_input = $('#priority_input');
    assign_to_input = $('#assign_to_input');
    start_date_input = $('#start_date_input');
    end_date_input = $('#end_date_input');
    estimate_input = $('#estimate_input');
    description = $('#description');
    attachment = $('#attachment');
    

    function update(value, field){
      update_url = $('#task-form').data('url');
      $.ajax({
        url: update_url,
        method: 'POST',
        dataType: 'json',
        data: {
          [field]: value
        },
        success: function(response) {
          console.log(response)
        },
        error: function(xhr) {
          console.error('Gagal update:', xhr.responseText);
        }
      })
    }

    function estimate(){
      let taskid = $('#taskid').data('taskid');
      let estimateurl = $('#taskid').data('estimateurl');
      let estimate_update_url = $('#taskid').data('estimate_update_url');
      
      //RENDER TAMPILAN ESTIMATE
      $.ajax({
        url: estimateurl,
        method: 'post',
        dataType: 'json',
        data: {
          task: taskid
        },
        success: function(response) {
          let estimate = response
          //==UPDATE DATABASE
          $.ajax({
            url: estimate_update_url,
            method: 'post',
            dataType: 'json',
            data: {
              estimate: estimate
            },
            success: function(response) {    
              console.log(response);                      
            },
            error: function(xhr) {
              console.error('GAGAL di update database:', xhr.responseText);
            }
          })
          //========
          
          $('#estimate_content').html(`${response} Day`);          
        },
        error: function(xhr) {
          console.error('GAGAL:', xhr.responseText);
        }
      })
      
      
    }

    nama_task_editable.on('blur', function(){
      console.log($(this).text());
      update($(this).text(), 'nama_task');
    })
    status_input.on('change', function() {
      update(status_input.val(), 'status')
    })

    priority_input.on('change', function() {
      update(priority_input.val(), 'priority')
    })

    assign_to_input.on('change', function() {
      update(assign_to_input.val(), 'assign_to')
    })

    start_date_input.on('change', function() {
      update(start_date_input.val(), 'start_date')
      estimate();
    })

    end_date_input.on('change', function() {
      update(end_date_input.val(), 'end_date')
      estimate();
    })

    estimate_input.on('change', function() {
      update(estimate_input.val(), 'estimate')
    })

    description.on('blur', function() {
      update(description.val(), 'description')
    })
    console.log(url)


attachment.on('change', function() {
  let files = attachment[0].files;
  let formData = new FormData();

  for (let i = 0; i < files.length; i++) {
    formData.append('attachment[]', files[i]);
  }

  let update_url = $('#task-form').data('url');

  $.ajax({
    url: update_url,
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if (response.attachments && response.attachments.length > 0) {
        let list = $('#attachment-list');
        $('#attachment-section').removeClass('d-none');

        response.attachments.forEach(file => {
          let ext = file.filename.split('.').pop().toUpperCase();

          // Cek apakah item sudah ada supaya tidak double
          if ($(`#attachment-item-${file.id}`).length === 0) {
            let item = `
              <li class="list-group-item d-flex justify-content-between align-items-center" id="attachment-item-${file.id}">
                <div class="d-flex align-items-center">
                  <i class="bi bi-paperclip me-2 text-primary"></i>
                  <a href="/storage/attachments/${file.filename}" target="_blank" class="text-decoration-none fw-medium">
                    ${file.filename}
                  </a>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-secondary">${ext}</span>
                  <button type="button" class="btn btn-sm btn-outline-danger delete-attachment" data-id="${file.id}">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </li>
            `;
            list.append(item);
          }
        });
      }
    },
    error: function(xhr) {
      console.error('Gagal upload file:', xhr.responseText);
    }
  });
});


$(document).on('click', '.delete-attachment', function () {
  let attachmentId = $(this).data('id');
  let deleteUrl = `/attachments/${attachmentId}`;

  if (!confirm('Yakin ingin menghapus file ini?')) return;

  $.ajax({
    url: deleteUrl,
    method: 'POST',
    data: {
      _method: 'DELETE',
      _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response) {
      console.log('Attachment berhasil dihapus:', response);
      // Optional: Hapus elemen dari DOM
      $(`button[data-id="${attachmentId}"]`).closest('li').remove();
    },
    error: function (xhr) {
      console.error('Gagal hapus attachment:', xhr.responseText);
    }
  });
});




    // Panggil saat halaman selesai dimuat
    loadComments();
    
  });
</script>



@endsection
