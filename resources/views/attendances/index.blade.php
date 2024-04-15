@extends('layouts.core')

@section('title', 'Metric Input Management')
    
@section('content')
    @include('attendances.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="overflow-auto">
                    <table class="table table-borderless datatable">
                        <thead>
                            <tr>
                                <th>#No</th>
                                <th>Date</th>
                                <th>Programme</th>
                                <th>Team</th>
                                <th>Memo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $i => $row)
                                <tr>
                                    <th style="height: {{ count($attendances) == 1? '80px': '' }}">{{ $i+1 }}</th>
                                    <td>{{ dateFormat($row->date) }}</td>
                                    <td>{{ @$row->programme->name }}</td>
                                    <td>{{ @$row->team->name }}</td>
                                    <td>{{ @$row->memo }}</td>
                                    <td>{!! $row->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
