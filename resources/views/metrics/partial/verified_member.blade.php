@foreach ($teamMembers as $key => $row)
  @php
    $checked = $isMetricEdit? optional($row->metricMembers->where('checked', 1))->first() : null;
  @endphp
<div class="col-12 col-md-6 col-lg-4">
  <div class="form-check border rounded px-3 py-2 h-100">
    <input name="team_member_id[]" id="member-{{ $row->id }}" class="form-check-input member-check" 
      type="checkbox" value="{{ $row->id }}" data-cat="{{ $row->category }}" {{ $checked? 'checked' : '' }}>                        
    <label class="form-check-label w-100" for="{{ $row->id }}">
      <div class="d-flex justify-content-between">
        <span>{{ $row->full_name }}</span>
        <small class="text-muted text-uppercase">{{ $row->category }}</small>
      </div>
    </label>
  </div>
</div>
@endforeach                

