@extends('layouts.core')
@section('title', 'Create | Team Management')
    
@section('content')
    @include('teams.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create Team</h5>
            <div class="card-content p-2">
                {{ Form::open(['route' => 'teams.store', 'method' => 'POST', 'class' => 'form']) }}
                    @include('teams.form')
                    <div class="text-center">
                        <a href="{{ route('teams.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
