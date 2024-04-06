@extends('layouts.core')
@section('title', 'Create | Teams')
    
@section('content')
    @include('team_labels.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create Team</h5>
            <div class="card-content p-2">
                {{ Form::open(['route' => 'team_labels.store', 'method' => 'POST', 'class' => 'form']) }}
                    @include('team_labels.form')
                    <div class="text-center">
                        <a href="{{ route('team_labels.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
