<!-- leaders retreat -->
<div class="card mb-3 leaders_retreat">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Leaders Retreat Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="retreat_meeting_no" class="col-md-2">No. of Meetings</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('retreat_meeting_no', null, ['class' => 'form-control', 'placeholder' => 'Number of meetings']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="retreat_meeting_no" class="col-md-2">No. of Leaders</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('retreat_leader_no', null, ['class' => 'form-control', 'placeholder' => 'Number of leaders']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="leaders_retreat_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('retreat_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- online meeting -->
<div class="card mb-3 online_meeting">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Online Meeting Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">No. meetings</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('online_meeting_no', null, ['class' => 'form-control', 'placeholder' => 'Number of meetings']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('online_meeting_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- team bonding -->
<div class="card mb-3 team_bonding">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Team Bonding Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">No. of activities</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('tb_activities_no', null, ['class' => 'form-control', 'placeholder' => 'Number of activities']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('tb_activities_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="extra_points" class="col-md-2">Extra Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('tb_activities_extra_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="extra_points" class="col-md-2">For No. Activities Above</label>
                <div class="col-md-8 col-12">
                    <div class="row g-1">
                        <div class="col-5">{{ Form::number('tb_activities_extra_min_no', null, ['class' => 'form-control', 'placeholder' => 'Min']) }}</div>
                        <div class="col-2 pt-1 text-center"><span>To Max</span></div>
                        <div class="col-5">{{ Form::number('tb_activities_extra_max_no', null, ['class' => 'form-control', 'placeholder' => 'Max']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- summit meeting -->
<div class="card mb-3 summit_meeting">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Summit Meeting Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">No. of meetings</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('summit_meeting_no', null, ['class' => 'form-control', 'placeholder' => 'Number of meetings']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="leaders_no" class="col-md-2">No. of leaders</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('summit_leaders_no', null, ['class' => 'form-control', 'placeholder' => 'Number of leaders']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="meeting_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('summit_meeting_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- recruitment -->
<div class="card mb-3 recruitment">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Recruitment Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="recruitment_no" class="col-md-2">No. of Recruits</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('recruit_no', null, ['class' => 'form-control', 'placeholder' => 'Number of recruits']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="recruitment_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('recruit_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="recruitment_no" class="col-md-2">Max Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('recruit_max_points', null, ['class' => 'form-control', 'placeholder' => 'Maximum Points']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- initiative -->
<div class="card mb-3 initiative">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">New Initiative Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="initiative_no" class="col-md-2">No. of Initiatives</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('initiative_no', null, ['class' => 'form-control', 'placeholder' => 'Number of new initiatives']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="initiative_no" class="col-md-2">Max No. Initiatives</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('initiative_max_no', null, ['class' => 'form-control', 'placeholder' => 'Maximum No. of initiatives']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="initiative_no" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('initiative_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- team mission -->
<div class="card mb-3 mission">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Mission Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="mission_no" class="col-md-2">No. of Missions</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('mission_no', null, ['class' => 'form-control', 'placeholder' => 'Number of missions']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="mission_score" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('mission_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <label for="mission_amount" class="col-md-2">Pledge Amount</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('mission_pledge', null, ['class' => 'form-control', 'placeholder' => 'Pledge Amount']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="mission_amount_score" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('mission_pledge_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- choir member -->
<div class="card mb-3 mission">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Choir Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="choir_no" class="col-md-2">Choir Members</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('choir_no', null, ['class' => 'form-control', 'placeholder' => 'Number of choir members']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="choir_score" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('choir_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- other activities -->
<div class="card mb-3 other_activities">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Other Activities Scale</h5>
        <div class="card-content p-2">
            <div class="row mb-3">
                <label for="other_activities_no" class="col-md-2">No. of Activities</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('other_activities_no', null, ['class' => 'form-control', 'placeholder' => 'Number of other activities']) }}
                </div>
            </div>
            <div class="row mb-3">
                <label for="other_activities_score" class="col-md-2">Score Points</label>
                <div class="col-md-8 col-12">
                    {{ Form::number('other_activities_score', null, ['class' => 'form-control']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- attendance -->
<div class="card mb-3 attendance">
    <div class="card-body mb-0 pb-0">
        <h5 class="card-title">Attendance Scale</h5>
        <div class="card-content p-2">
            <div style="width:70%; margin-left:100px;">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Percentage Score Points (%)</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($score_card->items) && count($score_card->items))
                            @foreach ($score_card->items as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6"><input type="number" name="min[]" value="{{ $item->min }}" class="form-control" placeholder="min"></div>
                                            <div class="col-md-6"><input type="number" name="max[]" value="{{ $item->max }}" class="form-control" placeholder="max"></div>
                                        </div>
                                    </td>
                                    <td><input type="number" name="point[]" value="{{ $item->point }}" class="form-control" placeholder="points"></td>
                                </tr>
                            @endforeach
                        @else
                            @foreach (range(1,10) as $item)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6"><input type="number" name="min[]" class="form-control" placeholder="min"></div>
                                            <div class="col-md-6"><input type="number" name="max[]" class="form-control" placeholder="max"></div>
                                        </div>
                                    </td>
                                    <td><input type="number" name="point[]" class="form-control" placeholder="points"></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
