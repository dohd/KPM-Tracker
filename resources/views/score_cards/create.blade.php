@extends('layouts.core')
@section('title', 'Create | Score Cards')
    
@section('content')
    @include('score_cards.header')
    {{ Form::open(['route' => 'score_cards.store', 'method' => 'POST', 'class' => 'form']) }}
        @include('score_cards.form')
        <div class="card">
            <div class="card-body">
                <div class="card-content p-2">
                    <div class="text-center mt-3">
                        <hr>
                        <a href="{{ route('score_cards.index') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@stop
