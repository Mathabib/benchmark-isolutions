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

    .form-container {
        background-color: #1e1e1e;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 25px rgba(255, 235, 59, 0.1);
        animation: fadeIn 0.6s ease-in-out;
    }

    label {
        font-weight: 500;
        color: #ffeb3b;
    }

    .form-control {
        background-color: #2a2a2a;
        border: 1px solid #444;
        color: #fff;
        padding: 0.65rem 0.75rem;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #ffeb3b;
        box-shadow: 0 0 0 0.2rem rgba(255, 235, 59, 0.2);
    }

    .btn {
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #ffca28;
        color: #000;
    }

    .btn-primary:hover {
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

    .form-group {
        margin-bottom: 1.2rem;
    }

    @keyframes slideDown {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="container py-4">
    <h1 class="mb-4">Edit User</h1>

    <div class="form-container">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Optional password update --}}
            <div class="form-group">
                <label for="password">Password Baru (opsional)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">
            </div>            

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>

    <div>
        <div>
            <h2>Project</h2>
        </div>

        <div>
            <table class="table">
                <thead>
                    <tr>
                        <td style="width: 50px">No</td>
                        <td>Projects</td>
                        <td style="width: 160px;">Action</td>
                    </tr>
                </thead>
                <tbody>
                    <tr id="add-project-button-row">
                        <td>{{ $projects->count() + 1 }}</td>
                        <td colspan="2">
                            <button id="btn-show-add" class="btn btn-sm btn-success">
                                <i class="bi bi-plus-lg"></i> Tambah Proyek
                            </button>
                        </td>                                
                    </tr>

                    <tr id="add-project-form-row" class="d-none">
                        <form action="{{ route('projects.give_access') }}" method="POST">
                            @csrf
                            <td></td>
                            {{-- <td><input type="text" name="nama" class="form-control" placeholder="Nama proyek" required></td>                                     --}}
                            <td>
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <select name="project_id" id="project_id" class="form-select">
                                    <option value="" selected>Project</option>
                                    @foreach ($projects as $project)                                                
                                        <option value="{{ $project->id }}">{{ $project->nama }}</option>
                                    @endforeach
                                    
                                </select>
                            </td>                                    
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
    
</div>

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
