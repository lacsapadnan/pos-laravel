@extends('layouts.master')

@section('title', 'Data Satuan')

@section('action')
@can('unit.create')
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDataModal">Tambah Data</button>
@include('pages.unit.modal-create')
@endcan

@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="units-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Satuan</th>
                        <th>Singkatan</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->name }}</td>
                        <td><span class="badge bg-primary text-white">{{ $unit->abbreviation }}</span></td>
                        <td>{{ $unit->description ?? '-' }}</td>
                        <td>
                            @canany(['unit.update', 'unit.delete'])
                            @can('unit.update')
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal-{{ $unit->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Edit
                            </button>

                            @include('pages.unit.modal-edit')
                            @endcan
                            @can('unit.delete')
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#delete-unit-{{ $unit->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                                Hapus
                            </button>

                            <x-modal.delete-confirm id="delete-unit-{{ $unit->id }}"
                                :route="route('unit.destroy', $unit->id)" item="{{ $unit->name }}" title="Hapus Satuan?"
                                description="Satuan yang dihapus tidak bisa dikembalikan." />
                            @endcan
                            @else
                            <span class="text-muted">Tidak ada aksi</span>
                            @endcanany
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
            $('#units-table').DataTable();
        });
</script>
@endpush