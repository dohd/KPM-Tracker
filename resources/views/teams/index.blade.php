@extends('layouts.core')
@section('title', 'Team Management')
    
@section('content')
    @include('teams.header')
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
                            @foreach ($teams as $i => $team)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('', $team->tid) }}</th>
                                    <td>{{ $team->name }}</td>
                                    @php $teamSize = $team->team_sizes->sortByDesc('start_period')->first() @endphp
                                    <td>{{ @$teamSize->local_size }}</td>
                                    <td>{{ @$teamSize->diaspora_size }}</td>
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
