@extends('layouts.core')

@section('title', 'Program Management')
    
@section('content')
    @include('programmes.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="table-responsive">
                    <table class="table table-borderless datatable">
                        <thead>
                        <tr>
                            <th>#No.</th>
                            <th>Code</th>
                            <th>Program</th>
                            <th>Status</th>
                            <th>Metric Type</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($programmes as $i => $programme)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('',$programme->tid) }}</th>
                                    <td>{{ $programme->name }}</td>
                                    <td>{!! $programme->is_active_status_budge !!}</td>
                                    <td>{{ $programme->metric }}</td>
                                    <td>{!! $programme->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
