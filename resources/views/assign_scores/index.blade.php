@extends('layouts.core')
@section('title', 'Assign Scores Management')
    
@section('content')
    @include('assign_scores.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="table-responsive">
                    <table class="table table-borderless datatable">
                        <thead>
                          <tr>
                            <th>#No.</th>
                            <th>Code</th>
                            <th>Program Name</th>
                            <th>Period From</th>
                            <th>Period To</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($assign_scores as $i => $row)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <td>{{ tidCode('', $row->tid) }}</td>
                                    <td>{{ @$row->programme->name }}</td>
                                    <td>{{ dateFormat($row->period_from) }}</td>
                                    <td>{{ dateFormat($row->period_to) }}</td>
                                    <td>{!! $team->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
