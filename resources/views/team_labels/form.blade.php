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
            @foreach (range(1,12) as $i => $item)
                <tr>
                    <td><input type="date" name="start_date[]" value="{{ @(explode(',', $team_label->start_date)[$i]) }}" class="form-control"></td>
                    <td><input type="number" name="local_size[]" value="{{ @(explode(',', $team_label->local_size)[$i]) }}" class="form-control" placeholder="Local Size"></td>
                    <td><input type="number" name="diaspora_size[]" value="{{ @(explode(',', $team_label->diaspora_size)[$i]) }}" class="form-control" placeholder="Diaspora Size"></td>
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
