@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Assets Register</h1>
            <a href="{{ route('assets.create') }}" class="btn btn-primary">+ Add Asset</a>
        </div>

        @if($assets->count())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>CIA Ratings</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ ucfirst($asset->asset_type) }}</td>
                                <td>
                                    C:{{ $asset->confidentiality_rating }}
                                    I:{{ $asset->integrity_rating }}
                                    A:{{ $asset->availability_rating }}
                                </td>
                                <td>
                                    <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">No assets found. Create your first asset now.</div>
        @endif
    </div>
@endsection