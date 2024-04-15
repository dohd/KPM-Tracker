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
                            <th>Team Label</th>
                            <th>Local Size</th>
                            <th>Diasp. Size</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($team_labels as $i => $team)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('', $team->tid) }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ last(explode(',', $team->local_size)) }}</td>
                                    <td>{{ last(explode(',', $team->diaspora_size)) }}</td>
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
