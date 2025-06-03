@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Project</h3>
        </div>
        <div class="card-body">
            {{-- Tampilkan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form edit project --}}
            <form action="{{ route('projects.update', $project->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Project <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            name="nama" 
                            id="nama" 
                            class="form-control @error('nama') is-invalid @enderror" 
                            value="{{ old('nama', $project->nama) }}" 
                            required 
                            placeholder="Masukkan nama project"
                        >
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <input 
                            type="text" 
                            name="deskripsi" 
                            id="deskripsi" 
                            class="form-control @error('deskripsi') is-invalid @enderror" 
                            value="{{ old('deskripsi', $project->deskripsi) }}" 
                            placeholder="Deskripsi singkat project"
                        >
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('projects.index2') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
