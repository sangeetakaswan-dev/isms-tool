@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Risk Register</h1>
            <div>
                <a href="{{ route('risks.heatmap') }}" class="btn btn-secondary">Heatmap</a>
                <a href="{{ route('risks.export.excel') }}" class="btn btn-success">Export Excel</a>
                <a href="{{ route('risks.create') }}" class="btn btn-primary">+ Add Risk</a>
            </div>
        </div>

        @if($risks->count())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Threat</th>
                            <th>Vulnerability</th>
                            <th>Risk Score</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risks as $risk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $risk->threat }}</td>
                                <td>{{ $risk->vulnerability }}</td>
                                <td>{{ $risk->risk_score }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $risk->risk_level == 'critical' ? 'danger' : ($risk->risk_level == 'high' ? 'warning' : ($risk->risk_level == 'medium' ? 'info' : 'success')) }}">
                                        {{ ucfirst($risk->risk_level) }}
                                    </span>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $risk->status)) }}</td>
                                <td>
                                    <a href="{{ route('risks.show', $risk) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('risks.edit', $risk) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('risks.destroy', $risk) }}" method="POST" class="d-inline">
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
            <div class="alert alert-info">No risks found. Start by identifying risks.</div>
        @endif
    </div>
@endsection