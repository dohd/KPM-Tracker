@extends('layouts.core')

@section('title', 'Edit | Metric Input Management')
    
@section('content')
    @include('attendances.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Metric Input Details</h5>
            <div class="card-content p-2">
                {{ Form::model($attendance, ['route' => ['attendances.update', $attendance], 'method' => 'PATCH', 'files' => true, 'class' => 'form']) }}
                    @include('attendances.form')
                    <div class="text-center">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
