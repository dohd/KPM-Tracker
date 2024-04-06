<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Team Scores</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="programme" class="col-md-2">Programme</label>
                <div class="col-md-8 col-12">
                    <select id="programme" class="form-control select2" data-placeholder="Choose Programme" required>
                        <option value=""></option>
                        @foreach ($programmes as $row)
                            <option value="{{ $row->id }}" {{ $row->id == @$attendance->programme_id? 'selected' : '' }}>
                                {{ tidCode('', $row->tid) }} - {{ $row->name }}
                            </option>
                        @endforeach
                    </select>   
                </div>
            </div>
            <div class="row mb-3">
                <label for="date" class="col-md-2">From Date</label>
                <div class="col-md-6 col-12">
                    {{ Form::date(null, null, ['class' => 'form-control', 'id' => 'date_from', 'required' => 'required']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="date" class="col-md-2">To Date</label>
                <div class="col-md-6 col-12">
                    {{ Form::date(null, null, ['class' => 'form-control date_to', 'id' => 'date_to', 'required' => 'required']) }}
                </div>
                <div class="col-md-2 col-12">
                    <button type="button" class="btn btn-success" id="load">Compute</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="card-content p-2 pt-4">
            <div class="table-responsive">
                <table class="table table-bordered" id="scores-tbl">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Team Name</th>
                            <th>Team Count</th>
                            <th>Points</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <hr>
            <div class="text-center">
                <a href="{{ route('assign_scores.create') }}" class="btn btn-secondary">Cancel</a>
                {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="load_score_status">

@section('script')
<script>
    let loadedScoresData = null;
    $('#load').click(function() {
        const programme_id = $('#programme').val();
        const date_from = $('#date_from').val();
        const date_to = $('#date_to').val();
        $('#scores-tbl tbody tr').remove();
        if (!(programme_id && date_from && date_to)) 
            return flashMessage({responseJSON:{message: 'Fields required! programme, from_date, to_date'}});
        const spinner = @json(spinner());
        $('#scores-tbl tbody').append(`<tr><td colspan="100%">${spinner}</td></tr>`);

        // fetch scores data
        $.ajax({
            url: "{{ route('assign_scores.load_scores') }}",
            method: 'POST',
            dataType: 'json',
            data: {programme_id, date_from, date_to},
            success: resp => {
                if (resp.flash_error) {
                    $('#scores-tbl tbody tr').remove();
                    return flashMessage({responseJSON:{message: resp.flash_error}});
                }
                if (resp.flash_success) {
                    loadedScoresData = resp.data;
                    $('#load_score_status').change();
                };
            },
            error: resp => {
                $('#scores-tbl tbody tr').remove();
                flashMessage({});
            },
        });
    });

    // hydrate score table
    $('#load_score_status').change(function() {
        $.ajax({
            url: "{{ route('assign_scores.load_scores_datatable') }}",
            method: 'POST',
            dataType: 'html',
            data: loadedScoresData,
            success: data => {
                $('#scores-tbl').html(data);
            },
            error: data => {
                $('#scores-tbl tbody tr').remove();
                flashMessage({});
            },
        });
    });
</script>
@stop
