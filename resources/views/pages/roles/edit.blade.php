@extends('layouts.master')

@section('title', 'Edit Permission Role')

@section('content')
<form action="{{ route('role.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        @foreach ($permissions as $category => $perms)
        <div class="col-md-3">
            <div class="mb-4 card">
                <div class="card-header">
                    <h3 class="card-title text-capitalize">{{ str_replace('_', ' ', $category) }}</h3>
                </div>
                <div class="card-body">
                    @foreach ($perms as $permission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]"
                            value="{{ $permission->name }}" id="perm-{{ $permission->id }}" {{
                            in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
@endsection