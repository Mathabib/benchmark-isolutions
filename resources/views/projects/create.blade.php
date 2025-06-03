@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Project Baru</h3>
        </div>
        <div class="card-body">
            {{-- Validasi error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('projects.store') }}" method="POST" novalidate>
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-semibold">Nama Project <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            name="nama" 
                            id="nama" 
                            class="form-control @error('nama') is-invalid @enderror" 
                            value="{{ old('nama') }}"    
                            required 
                            autofocus
                            placeholder="Masukkan nama project"
                        >
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                        <input 
                            type="text" 
                            name="deskripsi" 
                            id="deskripsi" 
                            class="form-control @error('deskripsi') is-invalid @enderror" 
                            value="{{ old('deskripsi') }}" 
                            placeholder="Deskripsi singkat project"
                        >
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('projects.index2') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold">
                        <i class="bi bi-check-circle"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
