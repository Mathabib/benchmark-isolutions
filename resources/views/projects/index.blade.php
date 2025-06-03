@extends('layouts.app')

@section('content')

<!-- Styles -->
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
        border-radius: 10px;
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

    .form-control {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #fff;
        padding: 0.6rem 0.75rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #ffeb3b;
        box-shadow: 0 0 0 0.2rem rgba(255, 235, 59, 0.2);
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn i {
        margin-right: 4px;
    }

    .btn-success, .btn-warning {
        background-color: #ffca28;
        color: #000;
    }

    .btn-success:hover,
    .btn-warning:hover {
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #f44336;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #e53935;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background-color: #757575;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #616161;
        transform: translateY(-2px);
    }

    .alert-success {
        background-color: #00c853;
        color: white;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        animation: bounceIn 0.5s ease;
    }

    .d-flex.gap-2 {
        display: flex;
        gap: 0.5rem;
    }

    #add-project-form-row,
    .edit-row {
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Animations */
    /* @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes slideDown {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes bounceIn {
        0%   { transform: scale(0.9); opacity: 0; }
        60%  { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(1); }
    } */
</style>

<!-- Content -->
<div class="container py-4">
    <h1 class="mb-4">Daftar Proyek</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Proyek</th>
                    <th>Deskripsi</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $index => $project)
                    <!-- Display Row -->
                    <tr data-id="{{ $project->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $project->nama }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($project->deskripsi, 150) }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-warning btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Form Row -->
                    <tr class="edit-row d-none" data-id="{{ $project->id }}">
                        <form action="{{ route('projects.update', $project->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td></td>
                            <td><input type="text" name="nama" class="form-control" value="{{ $project->nama }}" required></td>
                            <td><input type="text" name="deskripsi" class="form-control" value="{{ $project->deskripsi }}"></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-success" title="Simpan">
                                        <i class="bi bi-check-lg"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary btn-cancel" title="Batal">
                                        <i class="bi bi-x-lg"></i> Batal
                                    </button>
                                </div>
                            </td>
                        </form>
                    </tr>
                @endforeach

                <!-- Add Project Button Row -->
                <tr id="add-project-button-row">
                    <td>{{ $projects->count() + 1 }}</td>
                    <td colspan="2">
                        <button id="btn-show-add" class="btn btn-sm btn-success">
                            <i class="bi bi-plus-lg"></i> Tambah Proyek
                        </button>
                    </td>
                    <td></td>
                </tr>

                <!-- Add Project Form Row -->
                <tr id="add-project-form-row" class="d-none">
                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        <td></td>
                        <td><input type="text" name="nama" class="form-control" placeholder="Nama proyek" required></td>
                        <td><input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi proyek"></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-success" title="Simpan">
                                    <i class="bi bi-check-lg"></i> Simpan
                                </button>
                                <button type="button" id="btn-cancel-add" class="btn btn-sm btn-secondary" title="Batal">
                                    <i class="bi bi-x-lg"></i> Batal
                                </button>
                            </div>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnShowAdd = document.getElementById('btn-show-add');
        const addFormRow = document.getElementById('add-project-form-row');
        const addBtnRow = document.getElementById('add-project-button-row');
        const btnCancelAdd = document.getElementById('btn-cancel-add');

        // Show add form
        btnShowAdd.addEventListener('click', () => {
            addBtnRow.classList.add('d-none');
            addFormRow.classList.remove('d-none');
        });

        // Cancel add form
        btnCancelAdd.addEventListener('click', () => {
            addFormRow.classList.add('d-none');
            addBtnRow.classList.remove('d-none');
            addFormRow.querySelector('input[name="nama"]').value = '';
            addFormRow.querySelector('input[name="deskripsi"]').value = '';
        });

        // Edit functionality
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', e => {
                const row = e.target.closest('tr');
                const id = row.dataset.id;
                row.style.display = 'none';
                document.querySelector(`tr.edit-row[data-id="${id}"]`).classList.remove('d-none');
            });
        });

        // Cancel edit
        document.querySelectorAll('.btn-cancel').forEach(button => {
            button.addEventListener('click', e => {
                const row = e.target.closest('tr.edit-row');
                const id = row.dataset.id;
                row.classList.add('d-none');
                document.querySelector(`tr[data-id="${id}"]`).style.display = '';
            });
        });
    });
</script>

@endsection
