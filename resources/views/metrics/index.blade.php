@extends('layouts.core')
@section('title', 'Metrics Management')
    
@section('content')
    @include('metrics.header')
    <!-- Filter Section -->
    <div class="card">
        <div class="card-body">
            <div class="card-content pt-3">
                <div class="row no-gutters">
                    <div class="col-md-6 col-6">
                        <div class="row g-2 align-items-center">
                          <div class="col-12 col-sm-auto">
                            <label class="me-sm-2">Date Range</label>
                          </div>
                          <div class="col-12 col-sm-auto">
                            <input type="date" id="dateFrom" class="form-control">
                          </div>
                          <div class="col-12 col-sm-auto">
                            <input type="date" id="dateTo" class="form-control">
                          </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-12 mb-1">
                        <select id="programme" class="form-control select2" data-placeholder="Choose Program">
                            <option value=""></option>
                            @foreach ($programmes as $row)
                                <option value="{{ $row->id }}">
                                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                                </option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="col-md-3 col-12 mb-1">
                        <select id="team" class="form-control select2" data-placeholder="Choose Team">
                            <option value=""></option>
                            @foreach ($teams as $row)
                                <option value="{{ $row->id }}">
                                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                                </option>
                            @endforeach
                        </select>   
                    </div>
                    <div class="col-md-2 col-12 mb-1">
                        <select id="scoreStatus" class="form-control" data-placeholder="Choose Status">
                            <option value="">-- Score Status --</option>
                            <option value="1">Scored</option>
                            <option value="2">N/Scored</option>
                        </select>   
                    </div>
                    <div class="col-md-2 col-12 mb-1">
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
                    <div class="mb-2">
                        <span id="approveBtn" role="button" class="badge bg-success"><i class="bi bi-check2-all"></i> Approve</span>
                        <span id="unapproveBtn" role="button" class="badge bg-danger"><i class="bi bi-x-octagon"></i> Unapprove</span>
                    </div>
                    <table id="metricsTbl" class="table table-borderless">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Date</th>
                                <th>Program</th>
                                <th>Metric Type</th>
                                <th>Team</th>
                                <th>Appr. Status</th>
                                <th>Score Status</th>
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
    {{ Form::open(['route' => 'metrics.approve', 'method' => 'POST', 'id' => 'approvalForm']) }}
        <input type="hidden" name="action" id="approveActn">
        <input type="hidden" name="metric_ids" id="metricIds">
    {{ Form::close() }}
@stop

@section('script')
<script>
    let dataTable;
    let metricIds = [];
    const initRow = $('#metricsTbl tbody tr:first').clone(); 
    setTimeout(() => fetchData(), 500);

    $(document).on('change', '#checkAll', function() {
        if ($(this).prop('checked')) {
            $('#metricsTbl .check-row:not(:disabled)').each(function() {
                $(this).prop('checked', true);
                const id = $(this).attr('data-id');
                metricIds.push(id);
            });
            $('#metricIds').val(metricIds.join(','));
        } else {
            $('#metricsTbl .check-row:checked').prop('checked', false);
            $('#metricIds').val('');
            metricIds = [];
        }
    });

    $(document).on('change', '.check-row', function() {
        if ($(this).attr('disabled')) return;
        const id = $(this).attr('data-id');
        if ($(this).prop('checked')) {
            metricIds.push(id);
        } else {
            metricIds.splice(metricIds.indexOf(id), 1);
        }
        $('#metricIds').val(metricIds.join(','));
    });

    $('#approveBtn, #unapproveBtn').click(function() {
        if (!$('#metricIds').val()) return alert('Select records to proceed!');
        if (confirm('Are you sure?')) {
            if ($(this).is('#approveBtn')) {
                $('#approveActn').val('approve');
            } else if ($(this).is('#unapproveBtn')) {
                $('#approveActn').val('unapprove');
            }
            $('#approvalForm').submit();
        }
    });

    $('#filterBtn').click(function () {
        if (dataTable) {
            dataTable.destroy();
            dataTable = null;
        }
        $('#metricsTbl tbody').html(initRow);
        setTimeout(() => fetchData(), 500);
    });
    
    let currentReq = null;
    function fetchData() {
        if (currentReq) currentReq.abort();

        currentReq = $.post("{{ route('metrics.get_data') }}", {
            date_from: $('#dateFrom').val(),
            date_to: $('#dateTo').val(),
            programme_id: $('#programme').val(),
            team_id: $('#team').val(),
            score_status: $('#scoreStatus').val(),
        })
        .done(data => {
            $('#metricsTbl tbody').html(data);
            dataTable = new simpleDatatables.DataTable('#metricsTbl', {
                columns: [
                    {select: 0, sortable: false},
                    {select: 8, sortable: false},
                ],
            });
        })
        .fail((xhr, status, err) => {
            if (status !== 'abort') {
                // flashMessage(data)
                $('#metricsTbl tbody').html('');
                dataTable = new simpleDatatables.DataTable('#metricsTbl');                
            }
        });
    }
</script>
@stop