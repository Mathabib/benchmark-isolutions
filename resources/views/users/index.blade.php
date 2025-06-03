@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: 'Poppins', sans-serif;
        color: #fff;
    }

    h1 {
        font-weight: 800;
        color: #ffca28;
        animation: slideDown 0.6s ease-out;
    }

    .table-responsive {
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 0 20px rgba(255, 235, 59, 0.1);
        animation: fadeIn 0.6s ease-in;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    thead {
        background-color: #111;
        color: #ffeb3b;
    }

    thead th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #ffeb3b;
    }

    tbody tr {
        background-color: #212121;
        transition: transform 0.3s ease;
    }

    tbody tr:hover {
        transform: scale(1.01);
        background-color: #292929;
    }

    tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #2c2c2c;
    }

    .alert-success {
        background-color: #00c853;
        color: white;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        animation: bounceIn 0.5s ease;
    }

    .alert-info {
        background-color: #2196f3;
        color: white;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
    }

    .btn {
        border: none;
        padding: 0.45rem 0.75rem;
        font-weight: bold;
        border-radius: 8px;
        transition: transform 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-primary {
        background-color: #ffca28;
        color: #000;
    }

    .btn-danger {
        background-color: #d32f2f;
        color: #fff;
    }

    .btn-warning {
        background-color: #ffa000;
        color: #000;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .d-flex.gap-2 {
        display: flex;
        gap: 0.5rem;
    }

    @keyframes slideDown {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes bounceIn {
        0% { transform: scale(0.9); opacity: 0; }
        60% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); }
    }
</style>

<div class="container py-4">
    <h1 class="mb-4">Daftar Users</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah User Baru
    </a>

    @if($users->count())
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada user. Silakan tambah user baru.
        </div>
    @endif
</div>

@endsection
