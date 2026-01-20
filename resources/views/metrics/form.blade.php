<div class="row mb-3">
    <label for="date" class="col-md-2">Date</label>
    <div class="col-md-8 col-12">
        {{ Form::date('date', null, ['class' => 'form-control', 'id' => 'date', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="programme" class="col-md-2">Program</label>
    <div class="col-md-8 col-12">
        <select name="programme_id" id="programme" class="form-control select2" data-placeholder="Choose Program" required>
            <option value=""></option>
            @foreach ($programmes as $row)
                <option value="{{ $row->id }}" metric="{{ $row->metric ?: 'Finance' }}" {{ $row->id == @$metric->programme_id? 'selected' : '' }}>
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
                <option value="{{ $row->id }}" {{ $row->id == @$metric->team_id? 'selected' : '' }}>
                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                </option>
            @endforeach
        </select>   
    </div>
</div>
<!-- Attendance metric -->
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
            {{ Form::text('grant_amount', null, ['id' => 'grant_amount', 'class' => 'form-control', 'placeholder' => 'Amount contributed', 'autocomplete' => 'false']) }}
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
    <div class="row mb-3">
        <label for="team_mission_amount" class="col-md-2">Pledge Amount</label>
        <div class="col-md-8 col-12">
            {{ Form::text('team_mission_amount', null, ['id' => 'team_mission_amount', 'class' => 'form-control', 'placeholder' => 'Amount Allocated', 'autocomplete' => 'false']) }}
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

<div class="mt-2 mb-3" style="width:85%; margin-left:auto; margin-right:auto">
    <div class="border rounded p-3 bg-white mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div class="fw-semibold">
                <i class="bi bi-check2-square"></i> Confirm Members
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary select-all">
                    <i class="bi bi-check-all"></i> Select All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary clear-all">
                    <i class="bi bi-x-circle"></i> Clear
                </button>
            </div>
        </div>

        <div class="row g-3 member-checkbox-grid">
        </div>                            
    </div>    
</div>

@section('script')
<script>
    // ========= count team members =========
    function countTeam() {
        let local = 0, diaspora = 0, dormant = 0, confirmed = 0;
        $('input.member-check:checked').each(function() {
            const cat = $(this).data('cat');
            if (cat === 'local') local++;
            if (cat === 'diaspora') diaspora++;
            if (cat === 'dormant') dormant++;                
            confirmed++;
        });
        $('input[name="team_total"]').val(confirmed);
    }

    // ========= render checkbox grid =========
    function renderMonthCheckboxes() {
        $('.member-checkbox-grid').html('');
        $.post("{{ route('metrics.verified_team_members') }}", {
            team_id: $('#team').val(),
            is_metric_edit: "{{ @$metric->id }}",
        })
        .then(resp => {
            $('.member-checkbox-grid').html(resp);
        })
        .fail((xhr, status, err) => console.log(err));
    }

    // ========= select/clear all =========
    $(document).on('click', '.select-all', function(){
        $('.member-checkbox-grid').find('input.member-check').prop('checked', true);
        countTeam();
    });

    $(document).on('click', '.clear-all', function(){
        $('.member-checkbox-grid').find('input.member-check').prop('checked', false);
        countTeam();
    });

    $(document).on('change', 'input.member-check', function(){
        countTeam();
    });

    $(document).on('change', 'input[name="team_total"]', function(){
        if ($('input.member-check').length) {
            countTeam();
        }
    });

    $('#team').change(function() {
        renderMonthCheckboxes();
    });

    $('#programme').change(function() {
        const metric = $(this).find(':selected').attr('metric');
        $('.metric').each(function() {
            if ($(this).attr('key') == metric) $(this).removeClass('d-none');
            else $(this).addClass('d-none');
        });
    });
    $('#programme').change();

    $('form').on('change', '#grant_amount,#team_mission_amount', function() {
        const val = accounting.unformat($(this).val());
        $(this).val(accounting.formatNumber(val));
    });

    // on editing
    const metric = @json(@$metric);
    if (metric?.id && metric.in_score) {
        $('#date').attr('readonly', true);
        $('.metric input').attr('readonly', true);
        $('#programme, #team').attr('disabled', true);
        const programmeInp = `<input type="hidden" name="programme_id" value="${$('#programme').val()}">`;
        const teamInp = `<input type="hidden" name="team_id" value="${$('#team').val()}">`;
        $('form').append(programmeInp + teamInp);
    }

    const metricMembers = @json($metric->metricMembers ?? []);
    if (metricMembers.length) {
        renderMonthCheckboxes();
    }
</script>
@stop
