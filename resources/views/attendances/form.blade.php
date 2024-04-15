<div class="row mb-3">
    <label for="date" class="col-md-2">Date</label>
    <div class="col-md-8 col-12">
        {{ Form::date('date', null, ['class' => 'form-control', 'id' => 'date', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="programme" class="col-md-2">Programme</label>
    <div class="col-md-8 col-12">
        <select name="programme_id" id="programme" class="form-control select2" data-placeholder="Choose Programme" required>
            <option value=""></option>
            @foreach ($programmes as $row)
                <option value="{{ $row->id }}" metric="{{ $row->metric ?: 'Finance' }}" {{ $row->id == @$attendance->programme_id? 'selected' : '' }}>
                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                </option>
            @endforeach
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="team" class="col-md-2">Team</label>
    <div class="col-md-8 col-12">
        <select name="team_id" id="team" class="form-control select2" data-placeholder="Choose Team" required>
            <option value=""></option>
            @foreach ($teams as $row)
                <option value="{{ $row->id }}" {{ $row->id == @$attendance->team_id? 'selected' : '' }}>
                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                </option>
            @endforeach
        </select>   
    </div>
</div>
<!-- attendance metric -->
<div class="metric d-none" key="Attendance">
    <div class="row mb-3">
        <label for="team_total" class="col-md-2">No. of Team</label>
        <div class="col-md-8 col-12">
            {{ Form::number('team_total', null, ['class' => 'form-control', 'placeholder' => 'No. of team members', 'autocomplete' => 'false']) }}
        </div>
    </div>
    <div class="row mb-3">
        <label for="guest_total" class="col-md-2">No. of Guest</label>
        <div class="col-md-8 col-12">
            {{ Form::number('guest_total', null, ['class' => 'form-control', 'id' => 'guest_total', 'placeholder' => 'No. of guest members', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- finance metric -->
<div class="metric d-none" key="Finance">
    <div class="row mb-3">
        <label for="amount" class="col-md-2">Grant Amount</label>
        <div class="col-md-8 col-12">
            {{ Form::number('grant_amount', null, ['class' => 'form-control', 'placeholder' => 'Amount contributed', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- leader retreat metric -->
<div class="metric d-none" key="Leader-Retreat">
    <div class="row mb-3">
        <label for="leader_total" class="col-md-2">No. of Leaders</label>
        <div class="col-md-8 col-12">
            {{ Form::number('retreat_leader_total', null, ['class' => 'form-control', 'placeholder' => 'No. of leaders', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<div class="metric d-none" key="Online-Meeting">
    <div class="row mb-3">
        <label for="team_total" class="col-md-2">No. of Team</label>
        <div class="col-md-8 col-12">
            {{ Form::number('online_meeting_team_total', null, ['class' => 'form-control', 'placeholder' => 'No. of team members', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- team bonding metric -->
<div class="metric d-none" key="Team-Bonding">
    <div class="row mb-3">
        <label for="activities_total" class="col-md-2">No. of Activities</label>
        <div class="col-md-8 col-12">
            {{ Form::number('activities_total', null, ['class' => 'form-control', 'placeholder' => 'No. of activities', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- summit meeting metric -->
<div class="metric d-none" key="Summit-Meeting">
    <div class="row mb-3">
        <label for="leader_total" class="col-md-2">No. of Leaders</label>
        <div class="col-md-8 col-12">
            {{ Form::number('summit_leader_total', null, ['class' => 'form-control', 'placeholder' => 'No. of leaders', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- member recruit metric -->
<div class="metric d-none" key="Member-Recruitment">
    <div class="row mb-3">
        <label for="recruit_total" class="col-md-2">No. of Recruits</label>
        <div class="col-md-8 col-12">
            {{ Form::number('recruit_total', null, ['class' => 'form-control', 'placeholder' => 'No. of recruits', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- new initiative metric -->
<div class="metric d-none" key="New-Initiative">
    <div class="row mb-3">
        <label for="initiative_total" class="col-md-2">No. of Initiatives</label>
        <div class="col-md-8 col-12">
            {{ Form::number('initiative_total', null, ['class' => 'form-control', 'placeholder' => 'No. of new initiatives', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- team mission metric -->
<div class="metric d-none" key="Team-Mission">
    <div class="row mb-3">
        <label for="team_mission" class="col-md-2">No. of Missions</label>
        <div class="col-md-8 col-12">
            {{ Form::number('team_mission_total', null, ['class' => 'form-control', 'placeholder' => 'No. of team missions', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- choir member metric -->
<div class="metric d-none" key="Choir-Member">
    <div class="row mb-3">
        <label for="choir_member" class="col-md-2">No. of Choir Members</label>
        <div class="col-md-8 col-12">
            {{ Form::number('choir_member_total', null, ['class' => 'form-control', 'placeholder' => 'No. of choir members', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<!-- other activities metric -->
<div class="metric d-none" key="Other-Activities">
    <div class="row mb-3">
        <label for="other_activities" class="col-md-2">No. of Other Activities</label>
        <div class="col-md-8 col-12">
            {{ Form::number('other_activities_total', null, ['class' => 'form-control', 'placeholder' => 'No. of other activities', 'autocomplete' => 'false']) }}
        </div>
    </div>
</div>
<div class="row mb-3">
    <label for="memo" class="col-md-2">Memo</label>
    <div class="col-md-8 col-12">
        {{ Form::textarea('memo', null, ['class' => 'form-control', 'rows' => '1']) }}
    </div>
</div>

@section('script')
<script>
    $('#programme').change(function() {
        const metric = $(this).find(':selected').attr('metric');
        $('.metric').each(function() {
            if ($(this).attr('key') == metric) $(this).removeClass('d-none');
            else $(this).addClass('d-none');
        });
    });
    $('#programme').change();

    // on editing
    const attendance = @json(@$attendance);
    if (attendance && attendance.id) {
        const isComputed = @json(@$is_computed);
        if (isComputed) {
            $('#date').attr('readonly', true);
            $('.metric input').attr('readonly', true);
            $('#programme, #team').attr('disabled', true);
            const programmeInp = `<input type="hidden" name="programme_id" value="${$('#programme').val()}">`;
            const teamInp = `<input type="hidden" name="team_id" value="${$('#team').val()}">`;
            $('form').append(programmeInp + teamInp);
        }
    }
</script>
@stop
