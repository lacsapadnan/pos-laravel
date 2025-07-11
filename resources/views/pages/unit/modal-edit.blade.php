<!-- Modal -->
<div class="modal fade" id="editModal-{{ $unit->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $unit->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('unit.update', $unit->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel-{{ $unit->id }}">Edit Satuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name-{{ $unit->id }}" class="form-label">Nama Satuan</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name-{{ $unit->id }}" name="name" value="{{ old('name', $unit->name) }}" required
                            placeholder="Contoh: Kilogram">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="abbreviation-{{ $unit->id }}" class="form-label">Singkatan</label>
                        <input type="text" class="form-control @error('abbreviation') is-invalid @enderror"
                            id="abbreviation-{{ $unit->id }}" name="abbreviation"
                            value="{{ old('abbreviation', $unit->abbreviation) }}" required placeholder="Contoh: kg">
                        @error('abbreviation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description-{{ $unit->id }}" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description-{{ $unit->id }}" name="description" rows="3"
                            placeholder="Deskripsi satuan (opsional)">{{ old('description', $unit->description) }}</textarea>
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
