@extends('layouts.app')

@section('content')

<style>
.kotainer_table{
    
}
</style>
@include('komponen.navbar_mode')
<div class="kontainer_table">
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Num</th>
            <th scope="col">Task</th>
            <th scope="col">Status</th>
            <th scope="col">Duration Estimate</th>
            <th scope="col">Start</th>
            <th scope="col">End</th>
            <th scope="col">Priority</th>
            <th scope="col">Assign to</th>
            </tr>
        </thead>
        <tbody>
        @foreach ( $tasks as $index => $item)
            <tr>
                    <td class="table-item">{{ $index }}</td>
                    <td class="table-item">{{ $item->nama_task }}</td>
                    <td class="table-item">
                        <span class="badge 
                            {{ $item->status == 'todo' ? 'text-bg-secondary' : ($item->status == 'inprogress' ? 'text-bg-warning' : 'text-bg-success') }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="table-item">{{ $item->estimate }} Hour</td>
                    <td class="table-item">{{ $item->start_date }}</td>
                    <td class="table-item">{{ $item->end_date }}</td>
                    <td class="table-item">{{ $item->priority }}</td>
                    <td class="table-item">{{ $item->assign_to }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    
@endsection

@push('js')


@endpush