@extends('layouts.core')
@section('title', 'Teams')
    
@section('content')
    @include('team_labels.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="table-responsive">
                    <table class="table table-borderless datatable">
                        <thead>
                          <tr>
                            <th>#No.</th>
                            <th>Code</th>
                            <th>Team Name</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($team_labels as $i => $team)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('', $team->tid) }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->total }}</td>
                                    <td>{!! $team->is_active_status_budge !!}</td>
                                    <td>{!! $team->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
