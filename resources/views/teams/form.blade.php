<div class="row mb-3">
    <label for="name" class="col-md-2">Team Name</label>
    <div class="col-md-8 col-12">
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="member_list" class="col-md-2">List of Members</label>
    <div class="col-md-8 col-12">
        {{ Form::textarea('member_list', null, ['class' => 'form-control', 'rows' => '1', 'required' => 'required']) }}
    </div>
</div>

<div class="row mb-3">
    <label for="guest" class="col-md-2">Max Guest Size</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_guest', null, ['class' => 'form-control', 'placeholder' => 'No. of maximum guest members', 'required' => 'required']) }}
    </div>
</div>

<div style="width:65%; margin-left:auto; margin-right:auto">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Starting Date</th>
                <th>Local Team Size</th>
                <th>Diaspora Team Size</th>
            </tr>
        </thead>
        <tbody>
            @php $n = 12 @endphp
            @foreach ($team->team_sizes as $row)
                @php $n-- @endphp
                @if ($row->in_score && auth()->user()->user_type != 'chair')
                    <tr>
                        <td><input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control" readonly></td>
                        <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" class="form-control" readonly></td>
                        <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" class="form-control" readonly></td>
                    </tr>
                @else
                    <tr>
                        <td><input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control"></td>
                        <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" class="form-control" placeholder="Local Size"></td>
                        <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" class="form-control" placeholder="Diaspora Size"></td>
                    </tr>
                @endif
            @endforeach
            @foreach (array_fill(0,$n,0) as $i)
                <tr>
                    <td><input type="date" name="start_date[]" value="" class="form-control"></td>
                    <td><input type="number" name="local_size[]" value="0" class="form-control" placeholder="Local Size"></td>
                    <td><input type="number" name="diaspora_size[]" value="0" class="form-control" placeholder="Diaspora Size"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('script')
<script>
    $('table tbody tr:first input').attr('required', true);
</script>
@stop
