@extends('layouts.core')

@section('title', 'Create | Metrics Management')
    
@section('content')
    @include('metrics.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Metric Details</h5>
            <div class="card-content p-2">
                {{ Form::open(['route' => 'metrics.store', 'method' => 'POST', 'files' => true, 'class' => 'form']) }}
                    @include('metrics.form')
                    <div class="text-center">
                        <a href="{{ route('metrics.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
