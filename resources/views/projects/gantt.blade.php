@extends('layouts.app')

@section('content')
@include('komponen.navbar_mode')

<style>
    .ganti_warna{
        color: red
    }
</style>
<div class="container py-4">
    <div class="card shadow-sm border-0 bg-dark text-warning" id="gantt_data" data-urlupdate="{{ route('gantt.update') }}" data-csrftoken="{{ csrf_token() }}">
        <div class="card-body">
            <h3 class="mb-4">📊 Gantt Chart untuk Proyek: <strong>{{ $project->nama }}</strong></h3>

            {{-- <pre class="bg-light p-3 rounded text-dark">{{ json_encode($ganttTasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre> --}}

            <div id="gantt" style="border: 2px dashed #ffc107; border-radius: 10px; background-color: #ffffff;"></div>
        </div>
    </div>
</div>

@endsection

@push('js')
<!-- Frappe Gantt CSS & JS -->
{{-- <link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.5.0/dist/frappe-gantt.css" />
<script src="https://unpkg.com/frappe-gantt@0.5.0/dist/frappe-gantt.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tasks = @json($ganttTasks);

        if (tasks.length > 0) {
            const gantt = new Gantt("#gantt", tasks, {
                view_mode: 'Day',
                popup_on: 'hover',
                view_mode_select: true,
                container_height: 700,
                on_click: function(task) {
                    window.location.href = `/tasks/${task.id}`;
                   
                },
                on_date_change: function(task, start, end) {
                    updateTaskDates(task.id, start, end);
                },
                custom_popup_html: null
            });
        } else {
            document.getElementById("gantt").innerHTML = "<p class='text-muted'>Tidak ada data tugas.</p>";
        }

        

        function updateTaskDates(taskId, start, end) {
            // const csrfToken = '{{ csrf_token() }}';
            const csrfToken = $('#gantt_data').data('csrftoken');
            const url = $('#gantt_data').data('urlupdate');
            
            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                contentType: "application/json",
                headers: {                    
                    "X-CSRF-TOKEN": csrfToken,
                },
                data: JSON.stringify({
                    id: taskId,
                    start: start.toISOString().split("T")[0],
                    end: end.toISOString().split("T")[0]
                }),
                success: function(response){
                    console.log("Update response:", response);
                },
                error: function(err) {
                    console.error("Error updating task:", err);
                    alert("Gagal menyimpan perubahan.");
                }
            })
        }
    });
</script>
@endpush
