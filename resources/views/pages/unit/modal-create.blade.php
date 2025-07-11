<!-- Modal -->
<div class="modal fade" id="createDataModal" tabindex="-1" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('unit.store') }}">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createDataModalLabel">Tambah Satuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Satuan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required placeholder="Contoh: Kilogram">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="abbreviation" class="form-label">Singkatan</label>
                        <input type="text" class="form-control @error('abbreviation') is-invalid @enderror"
                            id="abbreviation" name="abbreviation" value="{{ old('abbreviation') }}" required
                            placeholder="Contoh: kg">
                        @error('abbreviation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" rows="3"
                            placeholder="Deskripsi satuan (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
