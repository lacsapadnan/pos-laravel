@extends('layouts.master')

@section('title', 'Pengaturan Website')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Website</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Website</label>
                        <input type="text" class="form-control @error('website_name') is-invalid @enderror"
                            name="website_name" value="{{ old('website_name', $setting?->website_name) }}"
                            placeholder="Masukkan nama website">
                        @error('website_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $setting?->email) }}" placeholder="Masukkan email">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone', $setting?->phone) }}" placeholder="Masukkan nomor telepon">
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Logo</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo"
                            accept="image/*">
                        @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($setting?->logo)
                        <div class="mt-2">
                            <small class="text-muted">Logo saat ini:</small><br>
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Current Logo" class="img-thumbnail"
                                style="max-width: 150px; max-height: 150px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Margin Penjualan (%)</label>
                        <input type="number" class="form-control @error('margin') is-invalid @enderror" name="margin"
                            value="{{ old('margin', $setting?->margin) }}" min="0" step="0.01" placeholder="Contoh: 20">
                        @error('margin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3"
                    placeholder="Masukkan alamat lengkap">{{ old('address', $setting?->address) }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection