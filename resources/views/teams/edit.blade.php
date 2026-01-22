@extends('layouts.core')
@section('title', 'Edit | Team Management')
    
@section('content')
    @include('teams.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Edit Team</h5>
            <div class="card-content p-2">
                {{ 
                    Form::model($team, [
                        'route' => ['teams.update', $team], 
                        'method' => 'PATCH', 
                        'class' => 'form',
                        'onsubmit' => "return confirm('Are you sure you want to submit these changes? Data may be overwritten')"
                    ]) 
                }}
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
