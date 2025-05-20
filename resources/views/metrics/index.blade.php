@extends('layouts.core')

@section('title', 'Metrics Management')
    
@section('content')
    @include('metrics.header')
    <!-- Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="card-content pt-3">
                <div class="row">
                    <div class="col-md-5 col-5">
                        <div class="d-flex justify-content-between">
                            <label>Date Range</label>
                            <input type="date" id="dateFrom">
                            <input type="date" id="dateTo">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-4">
                        <select id="programme" class="form-control select2" data-placeholder="Choose Program">
                            <option value=""></option>
                            @foreach ($programmes as $row)
                                <option value="{{ $row->id }}">
                                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                                </option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="col-md-3 col-3">
                        <select id="team" class="form-control select2" data-placeholder="Choose Team">
                            <option value=""></option>
                            @foreach ($teams as $row)
                                <option value="{{ $row->id }}">
                                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                                </option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="col-md-2 col-2">
                        <select id="scoreStatus" class="form-control" data-placeholder="Choose Status">
                            <option value="">-- Score Status --</option>
                            <option value="1">Scored</option>
                            <option value="2">N/Scored</option>
                        </select>   
                    </div>
                    <div class="col-md-2 col-2">
                        <button type="button" id="filterBtn" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="overflow-auto">
                    <table id="metricsTbl" class="table table-borderless">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Date</th>
                                <th>Program</th>
                                <th>Metric Type</th>
                                <th>Team</th>
                                <th>Status</th>
                                <th>Memo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="100%">{!! spinner() !!}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
    const initRow = $('#metricsTbl tbody tr:first').clone(); 
    let dataTable;

    fetchData();
    $('#filterBtn').click(function () {
        if (dataTable) dataTable.destroy();
        $('#metricsTbl tbody').html(initRow);
        fetchData();
    });

    
    function fetchData() {
        $.post("{{ route('metrics.get_data') }}", {
            date_from: $('#dateFrom').val(),
            date_to: $('#dateTo').val(),
            programme_id: $('#programme').val(),
            team_id: $('#team').val(),
            score_status: $('#scoreStatus').val(),
        })
        .done(data => {
            $('#metricsTbl tbody').html(data);
            dataTable = new simpleDatatables.DataTable('#metricsTbl');
        })
        .fail((xhr, status, err) => {
            // flashMessage(data)
            $('#metricsTbl tbody').html('');
            dataTable = new simpleDatatables.DataTable('#metricsTbl');
        });
    }
</script>
@stop