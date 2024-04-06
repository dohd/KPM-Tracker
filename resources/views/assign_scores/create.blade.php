@extends('layouts.core')
@section('title', 'Compute Scores')
    
@section('content')
    @include('assign_scores.header')
    {{ Form::open(['route' => 'assign_scores.store', 'method' => 'POST', 'class' => 'form']) }}
        @include('assign_scores.form')
    {{ Form::close() }}
@stop
