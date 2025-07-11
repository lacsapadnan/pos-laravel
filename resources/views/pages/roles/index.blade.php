@extends('layouts.master')

@section('title', 'Role & Permission')

@section('content')
<div class="card">
    <div class="card-body">
        <div id="table-default" class="table-responsive">
            <table id="roles-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Role</th>
                        @can('role.update')
                        <th>Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="table-tbody">
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        @can('role.update')
                        <td>
                            <a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>Edit Permission
                            </a>
                        </td>
                        @endcan
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data role</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
            $('#roles-table').DataTable({
                // opsi konfigurasi tambahan bisa ditambah di sini, misal:
                // paging: true,
                // searching: true,
                // ordering: true,
            });
        });
</script>
@endpush