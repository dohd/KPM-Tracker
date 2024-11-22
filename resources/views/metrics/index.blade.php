@extends('layouts.core')

@section('title', 'Metrics Management')
    
@section('content')
    @include('metrics.header')
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
                                <th>Amount</th>
                                <th>Team</th>
                                <th>Memo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metrics as $i => $row)
                                <tr>
                                    <th style="height: {{ count($metrics) == 1? '80px': '' }}">{{ $i+1 }}</th>
                                    <td style="width:10%">{{ dateFormat($row->date) }}</td>
                                    <td>{{ @$row->programme->name }}</td>
                                    @php $metric = @$row->programme->metric @endphp
                                    @if (in_array($metric, ['Finance', 'Team-Mission']))
                                        <td>{{ $metric == 'Finance'? numberFormat($row->grant_amount) : numberFormat($row->team_mission_amount) }}</td>
                                    @else
                                        <td></td>
                                    @endif
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
