@switch($input['metric'])
    @case('Attendance')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>Team Size</th>
                <th>Total Team Att.</th>
                @if ($input['programme_include_choir'])
                    <th>Total Average Att.</th>
                @else
                    <th>Total Guest Att.</th>
                    <th>Total Average Att.</th>
                    <th>% Score</th>
                @endif
                <th>Points</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $n = 0; @endphp
            @foreach ($teams as $i => $item)
                @php $n++; @endphp
                <tr>
                    <td>{{ $n }}</td>
                    <td class="fw-bold">{{ $item->name }}</td>
                    <td>{{ $item->total }}</td>
                    <td class="fw-bold">{{ $item->team_total_att }}</td>
                    @if ($input['programme_include_choir'])
                        <td class="fw-bold">{{ $item->team_avg_att }}</td>
                    @else
                        <td>{{ $item->guest_total_att }}</td>
                        <td class="fw-bold">{{ $item->team_avg_att }}</td>
                        <td>{{ $item->perc_score }}</td>
                    @endif
                    <td>{{ $item->points }}</td>
                    <td class="fw-bold">{{ $item->net_points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $item->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="team_total[]" value="{{ $item->total }}">
                    <input type="hidden" name="team_total_att[]" value="{{ $item->team_total_att }}">
                    <input type="hidden" name="guest_total_att[]" value="{{ $item->guest_total_att }}">
                    <input type="hidden" name="days[]" value="{{ $item->days }}">
                    <input type="hidden" name="avg_total_att[]" value="{{ $item->team_avg_att }}">
                    <input type="hidden" name="perc_score[]" value="{{ $item->perc_score }}">
                    <input type="hidden" name="point[]" value="{{ $item->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $item->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break

    @case('Finance')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>Overall Target</th>
                <th>Accrued Amount</th>
                <th>Points</th>
                <th>Extra Points</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $n = 0; @endphp
            @foreach ($teams as $i => $team)
                @php $n++; @endphp
                <tr>
                    <td>{{ $n }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ numberFormat($input['target_amount']) }}</td>
                    <td>{{ numberFormat($team->accrued_amount) }}</td>
                    <td>{{ $team->points }}</td>
                    <td>{{ $team->extra_points }}</td>
                    <td>{{ $team->net_points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="accrued_amount[]" value="{{ $team->accrued_amount }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="extra_points[]" value="{{ $team->extra_points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break

    @case('Leader-Retreat')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>Leader Attendance</th>
                <th>No. of Meetings</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->no_leaders }}</td>
                    <td>{{ $team->no_meetings }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="retreat_leader_total[]" value="{{ $team->no_leaders }}">
                    <input type="hidden" name="retreat_meeting_total[]" value="{{ $team->no_meetings }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('Online-Meeting')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Meetings</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->no_meetings }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="online_meeting_total[]" value="{{ $team->no_meetings }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('Team-Bonding')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Activities</th>
                <th>Points</th>
                <th>Extra Points</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->tb_activities_total }}</td>
                    <td>{{ $team->points }}</td>
                    <td>{{ $team->extra_points }}</td>
                    <td>{{ $team->net_points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="tb_activities_total[]" value="{{ $team->tb_activities_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="extra_points[]" value="{{ $team->extra_points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('Summit-Meeting')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Meetings</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->summit_meetings_total }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="summit_meetings_total[]" value="{{ $team->summit_meetings_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('Member-Recruitment')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Recruits</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->recruits_total }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="recruits_total[]" value="{{ $team->recruits_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('New-Initiative')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Initiatives</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->initiatives_total }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="initiatives_total[]" value="{{ $team->initiatives_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @case('Team-Mission')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Missions</th>
                <th>Pledged Amount</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->missions_total }}</td>
                    <td>{{ numberFormat($team->pledged_total) }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="team_missions_total[]" value="{{ $team->missions_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break   

    @case('Choir-Member')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Choir Members</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->choir_members_total }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="choir_members_total[]" value="{{ $team->choir_members_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break  
        
    @case('Other-Activities')
        <thead>
            <tr>
                <th>No.</th>
                <th>Team Name</th>
                <th>No. of Other Activities</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $i => $team)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->other_activities_total }}</td>
                    <td>{{ $team->points }}</td>
                    <input type="hidden" name="programme_id[]" value="{{ $input['programme_id'] }}">
                    <input type="hidden" name="team_id[]" value="{{ $team->id }}">
                    <input type="hidden" name="date_from[]" value="{{ $input['date_from'] }}">
                    <input type="hidden" name="date_to[]" value="{{ $input['date_to'] }}">
                    <input type="hidden" name="rating_scale_id[]" value="{{ $input['rating_scale_id'] }}">
                    <input type="hidden" name="other_activities_total[]" value="{{ $team->other_activities_total }}">
                    <input type="hidden" name="point[]" value="{{ $team->points }}">
                    <input type="hidden" name="net_points[]" value="{{ $team->net_points }}">
                </tr>
            @endforeach
        </tbody>
        @break    

    @default
    <thead>
        <tr>
            <th>No.</th>
            <th>Team Name</th>
            <th>Points</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody></tbody>
@endswitch
