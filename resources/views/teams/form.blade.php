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
    <table id="teamSizeTbl" class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Beginning</th>
                <th>Local Size</th>
                <th>Diaspora Size</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (@$team)
                @foreach ($team->team_sizes->sortByDesc('start_period') as $row)
                    {{-- check if team size has been used in scoring and user is not the chair --}}
                    @if ($row->in_score && auth()->user()->user_type != 'chair')
                        <tr>
                            <td><input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control" readonly></td>
                            <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" class="form-control" readonly></td>
                            <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" class="form-control" readonly></td>
                            <td>
                                <button type="button" class="btn btn-primary add-row"><i class="bi bi-plus-circle"></i></button>
                                <button type="button" class="btn btn-danger del-row" disabled><i class="bi bi-dash-circle"></i></button>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td><input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control"></td>
                            <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" class="form-control"></td>
                            <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" class="form-control"></td>
                            <td>
                                <button type="button" class="btn btn-primary add-row"><i class="bi bi-plus-circle"></i></button>
                                <button type="button" class="btn btn-danger del-row"><i class="bi bi-dash-circle"></i></button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
            <!-- row template -->
            <tr class="d-none" temp='1'>
                <td><input type="date" name="start_date[]" value="" class="form-control"></td>
                <td><input type="number" name="local_size[]" value="0" class="form-control" placeholder="Local Size"></td>
                <td><input type="number" name="diaspora_size[]" value="0" class="form-control" placeholder="Diaspora Size"></td>
                <td>
                    <button type="button" class="btn btn-primary add-row"><i class="bi bi-plus-circle"></i></button>
                    <button type="button" class="btn btn-danger del-row"><i class="bi bi-dash-circle"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@section('script')
<script>
    $(document).on('click', '.add-row, .del-row', function() {
        const tr = $(this).parents('tr');
        if ($(this).is('.add-row')) {
            tr.before(tempRow.clone().removeClass('d-none'));
        } else {
            if (!tr.prev().length && tr.next().attr('temp')) return;
            tr.remove();
        }
    });

    $('#teamSizeTbl tbody tr:first input').attr('required', true);
    const tempRow = $('#teamSizeTbl').find('tr.d-none');
    const team = @json(@$team);
    if (!team) tempRow.find('.add-row').click();

</script>
@stop
