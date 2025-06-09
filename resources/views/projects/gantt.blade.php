@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/frappe-gantt/dist/frappe-gantt.css">
    <div id="gantt"></div>

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="https://unpkg.com/frappe-gantt/dist/frappe-gantt.min.js"></script>

    <script>
        $(document).ready(function () {
            const tasks = @json($tasks ?? []); // jika $tasks tidak ada, default ke array kosong
            new Gantt("#gantt", tasks);
        });
    </script>

@endsection
