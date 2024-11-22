@extends('layouts.core')

@section('title', 'Edit | Program Management')
    
@section('content')
    @include('programmes.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Program Details</h5>
            <div class="card-content p-2">
                {{ Form::model($programme, ['route' => ['programmes.update', $programme], 'method' => 'PATCH', 'class' => 'form']) }}
                    @include('programmes.form')
                    <div class="text-center">
                        <a href="{{ route('programmes.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
