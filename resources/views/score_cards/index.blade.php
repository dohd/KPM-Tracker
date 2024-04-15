@extends('layouts.core')
@section('title', 'Score Cards')
    
@section('content')
    @include('score_cards.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="table-responsive">
                    <table class="table table-borderless datatable">
                        <thead>
                          <tr>
                            <th>#No.</th>
                            <th>Code</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($rating_scales as $i => $row)
                                <tr>
                                    <th scope="row">{{ $i+1 }}</th>
                                    <td>{{ tidCode('', $row->tid) }}</td>
                                    <td>{!! $row->is_active_status_budge !!}</td>
                                    <td>{{ dateFormat($row->created_at, 'd-M-Y') }}</td>
                                    <td>{{ dateFormat($row->updated_at, 'd-M-Y') }}</td>
                                    <td>{!! $row->action_buttons !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('score_cards.partial.status_modal')
@stop

@section('script')
<script>
    $('.modal-btn').click(function() {
        $('#status-form').attr('action', $(this).attr('data-url'));
    });
</script>
@stop
