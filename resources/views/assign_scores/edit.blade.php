@extends('layouts.core')
@section('title', 'Edit | Assign Scores')
    
@section('content')
    @include('assign_scores.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Team Details</h5>
            <div class="card-content p-2">
                {{ Form::model($assign_score, ['route' => ['assign_scores.update', $assign_score], 'method' => 'PATCH', 'class' => 'form']) }}
                    @include('assign_scores.form')
                    <div class="text-center">
                        <a href="{{ route('assign_scores.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
