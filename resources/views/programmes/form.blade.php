<div class="row mb-3">
    <label for="name" class="col-md-2">Programme Name</label>
    <div class="col-md-8 col-12">
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Max Aggregate Score</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_aggr_score', null, ['class' => 'form-control', 'placeholder' => 'Maximum aggregate score']) }}
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
    <label for="period" class="col-md-2">Computation Type</label>
    <div class="col-md-8 col-12">
        <select name="compute_type" id="compute_type" class="form-control select2" data-placeholder="Computation Type" autocomplete="false" required>
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
        <div class="row g-1">
            <div class="col-md-4" style="margin-right: 10px;">{{ Form::date('period_from', null, ['class' => 'form-control', 'id' => 'period_from', 'required' => 'required']) }}</div>
            <div class="col-md-3"><label for="period_to">Computation To</label></div>
            <div class="col-md-4">{{ Form::date('period_to', null, ['class' => 'form-control', 'id' => 'period_to', 'required' => 'required']) }}</div>
        </div>
    </div>
</div>


<!-- finance section -->
<div id="fin-section" class="d-none">
    <div class="row mb-3">
        <label for="amount" class="col-md-2">Overall Target</label>
        <div class="col-md-8 col-12">
            {{ Form::number('target_amount', null, ['class' => 'form-control', 'id' => 'target_amount', 'placeholder' => 'Overall Target Amount']) }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="target_condition" class="col-md-2">Target %</label>
        <div class="col-md-8 col-12">
            <div class="row g-1">
                <div class="col-md-5">{{ Form::number('amount_perc', null, ['class' => 'form-control', 'id' => 'amount_perc', 'placeholder' => 'Target Amount']) }}</div>
                <div class="col-md-1 text-center pt-1">By</div>
                <div class="col-md-6">{{ Form::date('amount_perc_by', null, ['class' => 'form-control', 'id' => 'amount_perc_by',]) }}</div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="score_points" class="col-md-2">Score Points</label>
        <div class="col-md-8 col-12">
            {{ Form::number('score', null, ['class' => 'form-control', 'id' => 'score', 'placeholder' => 'Score Points']) }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="score_points" class="col-md-2">Extra Points</label>
        <div class="col-md-8 col-12">
            {{ Form::number('extra_score', null, ['class' => 'form-control', 'placeholder' => 'Extra Points']) }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="target_condition" class="col-md-2">Extra Points For Every %</label>
        <div class="col-md-8 col-12">
            <div class="row g-1">
                <div class="col-md-5">{{ Form::number('every_amount_perc', null, ['class' => 'form-control', 'placeholder' => 'Amount']) }}</div>
                <div class="col-md-2 text-center pt-1">Above %</div>
                <div class="col-md-5">{{ Form::number('above_amount_perc', null, ['class' => 'form-control', 'placeholder' => 'Amount']) }}</div>
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
<!-- end finance section -->
<div class="row mb-3">
    <label for="name" class="col-md-2">Memo</label>
    <div class="col-md-8 col-12">
        {{ Form::textarea('memo', null, ['class' => 'form-control', 'rows' => '1']) }}
    </div>
</div>

@section('script')
<script>
    $('#metric').change(function() {
        if ($(this).val() == 'Finance') {
            $('#fin-section').removeClass('d-none');
            ['#score', '#target_amount', '#amount_perc', '#amount_perc_by']
            .forEach(v => $(v).attr('required', true));
        } else {
            $('#fin-section').addClass('d-none');
            ['#score', '#target_amount', '#amount_perc', '#amount_perc_by']
            .forEach(v => $(v).attr('required', false));
        }
    });
    $('#metric').change();

    // edit mode
    const programme = @json(@$programme);
    if (programme && programme.id) {
        $('#metric').val(programme.metric).change();
    }
</script>
@stop