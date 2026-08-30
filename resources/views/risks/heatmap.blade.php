@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Risk Heatmap</h1>
        <p>5x5 Risk Matrix</p>
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Impact / Likelihood</th>
                        <th>1 (Very Low)</th>
                        <th>2 (Low)</th>
                        <th>3 (Medium)</th>
                        <th>4 (High)</th>
                        <th>5 (Very High)</th>
                    </tr>
                </thead>
                <tbody>
                    @for($impact = 5; $impact >= 1; $impact--)
                        <tr>
                            <th>Impact {{ $impact }}</th>
                            @for($likelihood = 1; $likelihood <= 5; $likelihood++)
                                @php
                                    $score = $likelihood * $impact;
                                    $color = $score >= 17 ? 'bg-danger text-white' :
                                        ($score >= 10 ? 'bg-warning' :
                                            ($score >= 5 ? 'bg-info' : 'bg-success text-white'));
                                @endphp
                                <td class="{{ $color }}">
                                    {{ $score }}
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <span class="badge bg-success">Low (1-4)</span>
            <span class="badge bg-info">Medium (5-9)</span>
            <span class="badge bg-warning">High (10-16)</span>
            <span class="badge bg-danger">Critical (17-25)</span>
        </div>
    </div>
@endsection