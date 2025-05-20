@foreach ($metrics as $i => $row)
    <tr>
        <td><input type="checkbox" data-id="{{ $row->id }}" class="check-row" {{ $row->in_score? 'disabled' : '' }}></td>
        <td style="width:10%">{{ dateFormat($row->date) }}</td>
        <td>{{ @$row->programme->name }}</td>
        <td>{{ @$row->programme->metric }}</td>
        <td>{{ @$row->team->name }}</td>
        <td>{!! $row->is_approved? '<span class="badge bg-success">Appr</span>' : '<span class="badge bg-secondary">N/Appr</span>' !!}</td>
        <td>{!! $row->in_score? '<span class="badge bg-success">Scored</span>' : '<span class="badge bg-secondary">N/Score</span>' !!}</td>
        <td>{{ @$row->memo }}</td>
        <td>{!! $row->action_buttons !!}</td>
    </tr>
@endforeach