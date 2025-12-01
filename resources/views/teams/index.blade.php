@extends('layouts.core')
@section('title', 'Team Management')
    
@section('content')
    @include('teams.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="mb-2">
                    @if (auth()->user()->user_type === 'chair')
                        <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#verifxnModal" style="cursor: pointer;">
                            Verify Teams <i class="bi bi-caret-down-fill"></i>
                        </span>                    
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless datatable">
                        <thead>
                          <tr>
                            <th>#No.</th>
                            <th>#Serial</th>
                            <th>Team Label</th>
                            <th>Local Size</th>
                            <th>Diasp. Size</th>
                            <th>Dorm. Size</th>
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $i => $team)
                                @php $teamSize = optional($team->teamSizesForPeriod(date('m'), date('Y'))->first()) @endphp
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('', $team->tid) }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $teamSize->local_size }}</td>
                                    <td>{{ $teamSize->diaspora_size }}</td>
                                    <td>{{ $teamSize->dormant_size }}</td>
                                    <td>
                                        <span class="badge bg-{{ $teamSize->verified? 'success' : 'secondary' }}">
                                            {{ $teamSize->verified? 'Verified' : 'Unverified' }}
                                        </span>
                                    </td>
                                    <td>{{ dateFormat($team->updated_at, 'd-m-Y') }}</td>
                                    <td>{!! $team->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('teams.modals.verification_modal')
@stop

@section('script')
<script>
    const Index = {
        initRow: $('.temp-row'),

        init() {
            $('.temp-row').remove();

            $('#month, #year').change(Index.onChangePeriod);
            $('#month').change();
            
            $('#teamSizeTbl').on('change', '.verified-check', Index.onChangeVerified);
        },

        onChangePeriod() {
            $('#teamSizeTbl tbody').html('');
            $.post("{{ route('verification_teams') }}", {
                month: $('#month').val(),
                year: $('#year').val(),
            })
            .then(resp => {
                if (resp.length) {
                    resp.forEach(team => {
                        const teamSize = team.team_size || {};
                        const date = teamSize?.start_period || '';
                        const row = Index.initRow.clone();
                        row.find('.name').html(team.name);
                        row.find('.date').html(date.split('-').reverse().join('-'));
                        row.find('.local-size').html(teamSize.local_size);
                        row.find('.diasp-size').html(teamSize.diaspora_size);
                        row.find('.dorm-size').html(teamSize.dormant_size);
                        row.find('.verified').val(teamSize.verified? 1 : '');
                        row.find('.verified-check').attr('checked', teamSize.verified? true : false);
                        row.find('.note').html(teamSize.verified_note);
                        row.find('.id').val(teamSize.id);
                        $('#teamSizeTbl tbody').append(row);
                    });
                }
            })
            .fail((xhr,status,err) => console.log(err))
        },

        onChangeVerified() {
            const tr = $(this).closest('tr');
            if ($(this).prop('checked')) {
                tr.find('.verified').val(1);
            } else {
                tr.find('.verified').val('');
            }
        },
    }
    $(Index.init);
</script>
@stop
