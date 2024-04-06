@extends('layouts.core')

@section('title', 'View | Attendance Management')
    
@section('content')
    @include('attendances.header')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Attendance Details</h5>
            <div class="card-content p-2">
                <table class="table table-bordered">
                    @php
                        $details = [
                            'Project Title' => @$attendance->proposal->title,
                            'Activity' => @$attendance->activity->name,
                            'Action Plan' => tidCode('', @$attendance->action_plan->tid) . '/' . dateFormat(@$attendance->action_plan->date, 'Y'),
                            'Date' => dateFormat($attendance->date, 'd-M-Y'),
                            'Prepared By' => $attendance->prepared_by,
                            'Attendance Sheet' => $attendance->doc_file,
                        ];
                    @endphp
                    @foreach ($details as $key => $val)
                        <tr>
                            <th width="30%">{{ $key }}</th>
                            <td>
                                @if ($key == 'Project Title' && $val)
                                    <a href="{{ route('proposals.show', $attendance->proposal) }}">{{ $val }}</a>
                                @elseif ($key == 'Attendance Sheet' && $val)
                                    <a href="{{ route('storage.file_download', 'attendance,' . $attendance->doc_file) }}" target="_blank">{{ $val }}<i class="bi bi-download h5 ms-2"></i></a>
                                    <span class="del ms-3" style="cursor: pointer;" name="doc_file"><i class="bi bi-trash text-danger icon-xs"></i></span>
                                @else  
                                    {{ $val}}  
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

                <!-- participants -->     
                <div class="table-responsive">
                    <table class="table table-cstm" id="participants_tbl">
                        <thead>
                            <tr class="table-primary">
                                <th>#</th>
                                <th>Region</th>
                                <th>Cohort</th>
                                <th width="10%">Age Group</th>
                                <th>Disability</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendance->items as $i => $item)
                                <tr>
                                    <td class="p-3 num">{{ $i+1 }}</td>
                                    <td>{{ @$item->region->name }}</td>
                                    <td>{{ @$item->cohort->name }}</td>
                                    <td>{{ @$item->age_group->bracket }}</td>
                                    <td>{{ @$item->disability->name }}</td>
                                    <td>{{ $item->male }}</td>
                                    <td>{{ $item->female }}</td>
                                    <td>{{ $item->total }}</td>                          
                                </tr>
                            @endforeach
                            <tr class="bg-light bg-gradient">
                                <td></td>
                                <td colspan="4"><b>Total</b></td>
                                <td><b>{{ $attendance->items->sum('male') }}</b></td>
                                <td><b>{{ $attendance->items->sum('female') }}</b></td>
                                <td><b>{{ $attendance->items->sum('total') }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
    $(document).on('click', '.del', function() {
        const field = $(this).attr('name');
        const attendance_id = @json($attendance->id);
        const url = @json(route('attendances.delete_file'));
        if (confirm('Are you sure?')) {
            $.post(url, {attendance_id, field})
            .done((data) => flashMessage(data))
            .catch((data) => flashMessage(data));
        }
    });
</script>
@endsection