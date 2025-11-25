@extends('layouts.core')
@section('title', 'Team Management')
    
@section('content')
    @include('teams.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="mb-2">
                    <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#verifxnModal" style="cursor: pointer;">
                        Verify Teams <i class="bi bi-caret-down-fill"></i>
                    </span>                    
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
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $i => $team)
                                @php $teamSize = $team->team_sizes->sortByDesc('start_period')->first() @endphp
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <th>{{ tidCode('', $team->tid) }}</th>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ @$teamSize->local_size }}</td>
                                    <td>{{ @$teamSize->diaspora_size }}</td>
                                    <td><span class="badge bg-secondary">Unverified</span></td>
                                    <td>{{ dateFormat($team->updated_at, 'd M Y') }}</td>
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
        init() {
            $('#verifxnModal').modal('show');
        },
    }
    $(Index.init);
</script>
@stop
