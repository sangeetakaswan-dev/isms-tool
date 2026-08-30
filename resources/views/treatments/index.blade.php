@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Treatment Plans</h1>
            <a href="{{ route('treatments.create') }}" class="btn btn-primary">+ Add Treatment</a>
        </div>

        @if($treatments->count())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Action</th>
                            <th>Risk</th>
                            <th>Assigned To</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($treatments as $treatment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $treatment->action }}</td>
                                <td>{{ $treatment->riskAssessment->threat ?? 'N/A' }}</td>
                                <td>{{ $treatment->assignedTo->name ?? 'Unassigned' }}</td>
                                <td>{{ $treatment->due_date->format('d-m-Y') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $treatment->status == 'completed' ? 'success' : ($treatment->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($treatment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline">
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
            <div class="alert alert-info">No treatment plans found.</div>
        @endif
    </div>
@endsection