<div class="modal fade" id="verifxnModal" tabindex="-1" aria-labelledby="verifxnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="verifxnModalLabel">Verify Team Composition</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {{ Form::open(['route' => 'verify_teams', 'id' => 'verifxForm']) }}
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-sm-8">
              <label for="colFormLabel" class="col-sm-2 col-form-label">Period</label>
              @php
                $currentYear = date('Y');
                $endYear = $currentYear - 10;
                $years = [];
                for ($year = $currentYear; $year >= $endYear; $year--) {
                  $years[] = $year;
                }
                $months = [
                  'January', 'February', 'March', 'April', 'May', 'June',
                  'July', 'August', 'September', 'October', 'November', 'December'
                ];
              @endphp
              <div class="d-flex gap-3">
                <select id="month" class="form-control w-50">
                  @foreach ($months as $i => $month)
                    <option value="{{ $i+1 }}" {{ date('F') === $month? 'selected' : '' }}>{{ $month }}</option>
                  @endforeach
                </select>
                <select id="year" class="form-control w-50">
                  @foreach ($years as $year)
                    <option value="{{ $year }}" {{ date('Y') === $year? 'selected' : '' }} >{{ $year }}</option>
                  @endforeach
                </select>                  
              </div>              
            </div>
          </div>
          <div class="input-group">
            <label for="colFormLabel" class="col-sm-2 col-form-label">Composition</label>
          </div>
          <div class="table-responsive">
            <table id="teamSizeTbl" class="table table-bordered table-sm">
              <thead>
                  <tr>
                      <th>Team Name</th>
                      <th>As Of Date</th>
                      <th>Local Size</th>
                      <th>Diaspora Size</th>
                      <th>Dormant Size</th>
                      <th>Verified</th>
                      <th>Note</th>
                  </tr>
              </thead>
              <tbody>
                  <tr class="temp-row">
                    <td class="name" width="20%"></td>
                    <td class="date" width="15%"></td>
                    <td class="local-size"></td>
                    <td class="diasp-size"></td>
                    <td class="dorm-size"></td>
                    <td class="d-flex justify-content-center align-items-center">
                      <input type="checkbox" class="form-check-input verified-check">
                      <input type="hidden" name="verified[]" class="verified">
                    </td>                        
                    <td><textarea name="verified_note[]" class="form-control note" rows="1"></textarea></td>
                    <input type="hidden" name="id[]" class="id">
                  </tr> 
              </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      {{ Form::close() }}
      </div>    
    </div>
  </div>
</div>