@extends('layouts.core')

@section('title', 'Create Program')
    
@section('content')
    @include('programmes.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Create Program</h5>
            <div class="card-content p-2">
                {{ Form::open(['route' => 'programmes.store', 'method' => 'POST', 'class' => 'form']) }}
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
