<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Monthly Team Scores <input type="button" value="Reset" class="btn btn-outline-danger float-end" id="reset"></h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="programme" class="col-md-2">Team Program</label>
                <div class="col-md-7 col-12">
                    <select id="programme" class="form-control select2" data-placeholder="Choose Program" required>
                        <option value=""></option>
                        @foreach ($programmes as $row)
                            <option value="{{ $row->id }}" {{ $row->id == @$metric->programme_id? 'selected' : '' }}>
                                {{ tidCode('', $row->tid) }} - {{ $row->name }}
                            </option>
                        @endforeach
                    </select>   
                </div>
                <div class="col-md-2">
                    <input type="button" value="Compute Scores" class="btn btn-success" id="load">
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
                {{ Form::submit('Submit Scores', ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="load_score_status">
<input type="hidden" name="metric_ids" id="metric_ids">
<input type="hidden" name="team_sizes_ids" id="team_sizes_ids">

@section('script')
<script>
    /**
    $('#date_from').change(function() {
        if (!this.value) return $('#date_to').val('');
        dfrom = new Date(this.value);
        date = new Date(dfrom.getFullYear(), dfrom.getMonth() + 1, 0);
        date = date.toLocaleDateString();
        dto = date.split('/').reverse().join('-');
        $('#date_to').val(dto);
    });
    */

    function validateRequiredInput() {
        const programme_id = $('#programme').val();
        if (programme_id) return true;
        flashMessage({responseJSON:{message: 'Fields required! programme'}}); 
        return false;
    }

    // reset scores
    $('#reset').click(function() {
        if (!validateRequiredInput()) return false;
        if (!confirm('Are you sure? This action will delete previously saved records')) return;
        $('#scores-tbl tbody tr').remove();
        const spinner = @json(spinner());
        $('#scores-tbl tbody').append(`<tr><td colspan="100%">${spinner}</td></tr>`);

        $.ajax({
            url: "{{ route('assign_scores.reset_scores') }}",
            method: 'POST',
            dataType: 'json',
            data: {programme_id: $('#programme').val()},
            success: data => {
                $('#scores-tbl tbody tr').remove();
                if (data.flash_error) return flashMessage({responseJSON:{message: data.flash_error}});
                if (data.flash_success) return flashMessage({message: data.flash_success});
            },
            error: data => {
                $('#scores-tbl tbody tr').remove();
                flashMessage({});
            },
        });

    });

    // compute scores
    let loadedScoresData = null;
    $('#load').click(function() {
        $('#scores-tbl tbody tr').remove();
        if (!validateRequiredInput()) return false;
        const spinner = @json(spinner());
        $('#scores-tbl tbody').append(`<tr><td colspan="100%">${spinner}</td></tr>`);

        $.ajax({
            url: "{{ route('assign_scores.load_scores') }}",
            method: 'POST',
            dataType: 'json',
            data: {
                programme_id: $('#programme').val(), 
            },
            success: data => {
                $('#metric_ids').val('');
                $('#team_sizes_ids').val('');
                if (data.flash_success) {
                    const payload = data.data;
                    $('#metric_ids').val(payload.req_input.metric_ids);
                    $('#team_sizes_ids').val(payload.req_input.team_sizes_ids);
                    // set loaded scores
                    loadedScoresData = payload;
                    $('#load_score_status').change();
                } else if (data.flash_error) {
                    $('#scores-tbl tbody tr').remove();
                    return flashMessage({responseJSON:{message: data.flash_error}});
                }
            },
            error: (xhr, status, error) => {
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
            error: (xhr, status, error) => {
                $('#scores-tbl tbody tr').remove();
                flashMessage({});
            },
        });
    });
</script>
@stop
