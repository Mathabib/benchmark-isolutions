@extends('layouts.app')

@section('content')
@include('komponen.navbar_mode')

<div class="container py-4">
    <div class="card shadow-sm border-0 bg-dark text-warning">
        <div class="card-body">
            <h3 class="mb-4">📊 Gantt Chart untuk Proyek: <strong>{{ $project->nama }}</strong></h3>

            {{-- <pre class="bg-light p-3 rounded text-dark">{{ json_encode($ganttTasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre> --}}

            <div id="gantt" style="height: 500px; border: 2px dashed #ffc107; border-radius: 10px; background-color: #1a1a1a;"></div>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- Frappe Gantt CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/frappe-gantt@0.5.0/dist/frappe-gantt.css" />
<script src="https://unpkg.com/frappe-gantt@0.5.0/dist/frappe-gantt.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tasks = @json($ganttTasks);

        if (tasks.length > 0) {
            const gantt = new Gantt("#gantt", tasks, {
                view_mode: 'Day',
                on_click: task => alert(`Klik: ${task.name}`),
                on_date_change: function(task, start, end) {
                    updateTaskDates(task.id, start, end);
                },
                custom_popup_html: null
            });
        } else {
            document.getElementById("gantt").innerHTML = "<p class='text-muted'>Tidak ada data tugas.</p>";
        }

        function updateTaskDates(taskId, start, end) {
            const csrfToken = '{{ csrf_token() }}';

            fetch("{{ route('gantt.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    id: taskId,
                    start: start.toISOString().split("T")[0],
                    end: end.toISOString().split("T")[0]
                })
            })
            .then(res => res.json())
            .then(data => {
                console.log("Update response:", data);
            })
            .catch(err => {
                console.error("Error updating task:", err);
                alert("Gagal menyimpan perubahan.");
            });
        }
    });
</script>
@endpush
