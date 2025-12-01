<div class="row mb-3">
    <label for="is_active" class="col-md-2">Is Active</label>
    <div class="col-md-8 col-12">
        {{ Form::checkbox('is_active', isset($programme->is_active)? $programme->is_active : 1, true, ['id' => 'is_active']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Program Name</label>
    <div class="col-md-8 col-12">
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-4">
        <label for="isCumulative" class="col-md-6">Is Cumulative</label>
        {{ Form::checkbox('is_cumulative', isset($programme->is_cumulative)? $programme->is_cumulative : 0, false, ['id' => 'isCumulative', 'class' => 'ms-2']) }}
    </div>
    <div class="col-md-6 d-none">
        <select name="cumulative_programme_id" id="cumulativeProgramme" class="form-control select2" data-placeholder="Search prior program to cumulate from">
            <option value=""></option>
            @foreach ($cumulativeProgrammes as $row)
                <option value="{{ $row->id }}" {{ @$programme->cumulative_programme_id == $row->id? 'selected' : '' }}>
                    {{ $row->name }}
                </option>
            @endforeach
        </select>  
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Max Aggr. Score</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_aggr_score', null, ['class' => 'form-control', 'placeholder' => 'Maximum aggregate score']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Max Aggr. Guest Score</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_guest_score', null, ['class' => 'form-control', 'placeholder' => 'Maximum scorable guest size']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Max Daily Guest Size</label>
    <div class="col-md-8 col-12">
        <div class="row">
            <div class="col-md-5">
                {{ Form::number('max_daily_guest_size', null, ['class' => 'form-control', 'placeholder' => 'Maximum daily guest size']) }}
            </div>
            <div class="col-md-3 text-end"><label for="guestScore">Max Daily Score</label></div>
            <div class="col-md-4">
                {{ Form::number('max_daily_guest_score', null, ['class' => 'form-control', 'placeholder' => 'Maximum daily score']) }}
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Metric</label>
    <div class="col-md-8 col-12">
        <select name="metric" id="metric" class="form-control select2" data-placeholder="Choose Metric" autocomplete="false" required>
            <option value=""></option>
            <option value="Finance">Finance</option>
            <option value="Attendance">Attendance</option>
            <option value="Leader-Retreat">Leader Retreat</option>
            <option value="Online-Meeting">Online Meeting</option>
            <option value="Team-Bonding">Team Bonding</option>
            <option value="Summit-Meeting">Summit Meeting</option>
            <option value="Member-Recruitment">Member Recruitment</option>
            <option value="New-Initiative">New Initiative</option>
            <option value="Team-Mission">Team Mission</option>
            <option value="Choir-Member">Choir Member</option>
            <option value="Other-Activities">Other Activities</option>
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="include_choir" class="col-md-2">Include Choir</label>
    <div class="col-md-8 col-12">
        <select name="include_choir" id="include_choir" class="form-control select2" autocomplete="false">
            @foreach (['No', 'Yes'] as $i => $item)
                <option value="{{ $i }}" {{ @$programme->include_choir == $i? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="team_size" class="col-md-2">Computation Size</label>
    <div class="col-md-8 col-12">
        <select name="team_size" id="team_size" class="form-control select2" autocomplete="false">
            @foreach (['local_size' => 'Local', 'diaspora_size' => 'Diaspora', 'total_size' => 'Total'] as $key => $item)
                <option value="{{ $key }}" {{ @$programme->team_size == $key? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="period" class="col-md-2">Computation Type</label>
    <div class="col-md-8 col-12">
        <select name="compute_type" id="compute_type" class="form-control select2" data-placeholder="-- Computation Type --" autocomplete="false" required>
            <option value=""></option>
            @foreach (['Daily', 'Monthly'] as $item)
                <option value="{{ $item }}" {{ @$programme->compute_type == $item? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="period_from" class="col-md-2">Computation From</label>
    <div class="col-md-8 col-12">
        <div class="row">
            <div class="col-md-5">
                {{ Form::date('period_from', null, ['class' => 'form-control', 'id' => 'period_from', 'required' => 'required']) }}
            </div>
            <div class="col-md-3 text-end"><label for="period_to">Computation To</label></div>
            <div class="col-md-4">
                {{ Form::date('period_to', null, ['class' => 'form-control', 'id' => 'period_to', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>


<!-- Finance section -->
<div id="fin-section" class="d-none">
    <div class="row mb-3">
        <label for="amount" class="col-md-2">Target</label>
        <div class="col-md-8 col-12">
            <div class="row">
                <div class="col-md-5">{{ Form::number('target_amount', null, ['class' => 'form-control', 'id' => 'target_amount', 'placeholder' => 'Overall Target Amount']) }}</div>
                <div class="col-md-3 text-center">By (Deadline)</div>
                <div class="col-md-4">{{ Form::date('amount_perc_by', null, ['class' => 'form-control', 'id' => 'amount_perc_by',]) }}</div>
            </div>
        </div>
    </div>
    <!-- Scoring Bands -->
    <div class="row mb-3">
        <div class="col-md-2">
            {{ Form::hidden('amount_perc', null, ['class' => 'form-control', 'id' => 'amount_perc']) }}
            {{ Form::hidden('score', null, ['class' => 'form-control', 'id' => 'score']) }}
            {{ Form::hidden('bandjson', null, ['class' => 'form-control', 'id' => 'bandJson']) }}
        </div>
        <div class="col-md-5 col-12">
            @include('programmes.partials.bands')
        </div>
    </div>
    
    <div class="row mb-3">
        <label for="score_points" class="col-md-2">Extra Points</label>
        <div class="col-md-8 col-12">
            <div class="row g-1">
                <div class="col-md-2">{{ Form::number('extra_score', null, ['class' => 'form-control', 'placeholder' => 'Extra Points']) }}</div>
                <div class="col-md-3 text-center pt-1">For Every %</div>
                <div class="col-md-2">{{ Form::number('every_amount_perc', null, ['class' => 'form-control', 'placeholder' => 'Amount']) }}</div>
                <div class="col-md-2 text-center pt-1">Above</div>
                <div class="col-md-3">{{ Form::number('above_amount', null, ['class' => 'form-control', 'placeholder' => 'Amount']) }}</div>
            </div>
        </div>
    </div>
    
    <div class="row mb-3">
        <label for="max_extra_points" class="col-md-2">Max Extra Points</label>
        <div class="col-md-8 col-12">
            {{ Form::number('max_extra_score', null, ['class' => 'form-control', 'placeholder' => 'Maximum Extra Points']) }}
        </div>
    </div>
</div>
<!-- End finance section -->

<div class="row mb-3">
    <label for="name" class="col-md-2">Memo</label>
    <div class="col-md-8 col-12">
        {{ Form::textarea('memo', null, ['class' => 'form-control', 'rows' => '2']) }}
    </div>
</div>

@section('script')
<script>
    const Form = {
        programme: @json(@$programme),

        init() {
            $('#metric').change(Form.onChangeMetric);
            $('#metric').change();

            $('#is_active').change(Form.onChangeIsActive);
            $('#isCumulative').change(Form.onChangeIsCumulative);
            $('#bandsTable').on('keyup', '.band-threshold, .band-points', Form.onKeyupBands);

            // Edit Mode
            Form.editMode();
        },

        editMode() {
            const data = Form.programme;
            if (data?.id) {
                $('#metric').val(data.metric).change();
                if (data.is_active) $('#is_active').prop('checked', true).change();
                else $('#is_active').prop('checked', false).change();

                if (data.is_cumulative) $('#isCumulative').prop('checked', true).change();
                else $('#isCumulative').prop('checked', false).change();
                $('#cumulativeProgramme').val(data.cumulative_programme_id).change();

                const bands = JSON.parse(data.bandjson) || [];
                bands.forEach((v,i) => {
                    $(`.band-threshold:eq(${i})`).val(v.threshold);
                    $(`.band-points:eq(${i})`).val(v.points);
                });
                const threshold = accounting.unformat($('#amount_perc').val());
                const points = accounting.unformat($('#score').val());
                if (threshold && points) {
                    $('.band-threshold:first').val(threshold);
                    $('.band-points:first').val(points).keyup();
                }
            }
        },

        onKeyupBands() {        
            const payload = [];
            $('#bandsTable tbody tr').each(function() {
                const threshold = accounting.unformat($(this).find('.band-threshold').val());
                const points = accounting.unformat($(this).find('.band-points').val());
                if ($(this).index() == 0) {
                    $('#amount_perc').val(threshold);
                    $('#score').val(points);
                }
                if (threshold && points) {
                    payload.push({threshold, points});
                }
            });
            $('#bandJson').val(JSON.stringify(payload));
        },

        onChangeMetric() {
            if (this.value == 'Finance') {
                $('#fin-section').removeClass('d-none');
                ['#score', '#target_amount', '#amount_perc', '#amount_perc_by']
                .forEach(v => $(v).attr('required', true));
            } else {
                $('#fin-section').addClass('d-none');
                ['#score', '#target_amount', '#amount_perc', '#amount_perc_by']
                .forEach(v => $(v).attr('required', false));
            }

            if (this.value == 'Attendance') {
                $('#include_choir').parents('div.row').removeClass('d-none');
                $('#team_size').parents('div.row').removeClass('d-none');
            } else {
                $('#include_choir').parents('div.row').addClass('d-none');
                $('#team_size').parents('div.row').addClass('d-none');
            }
        },

        onChangeIsActive() {
            if ($(this).prop('checked')) $(this).attr('value', 1);
            else $(this).attr('value', 0);
        },

        onChangeIsCumulative() {
            $('#cumulativeProgramme').val('').change();
            const div = $('#cumulativeProgramme').parents('div:first');
            if ($(this).is(':checked')) {
                $('#isCumulative').val(1);
                div.removeClass('d-none');
            } else {
                $('#isCumulative').val(0);
                div.addClass('d-none');
            }
        },
    };

    $(Form.init);
</script>
@stop