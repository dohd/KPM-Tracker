<div class="modal fade" id="verifxnModal" tabindex="-1" aria-labelledby="verifxnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="verifxnModalLabel">Verify Team Composition</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>  
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
                <select name="month" id="month" class="form-control w-50">
                  @foreach ($months as $i => $month)
                    <option value="{{ $i+1 }}" {{ date('F') === $month? 'selected' : '' }}>{{ $month }}</option>
                  @endforeach
                </select>
                <select name="year" id="year" class="form-control w-50">
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
                      <th>Team</th>
                      <th>Beginning Date</th>
                      <th>Local Size</th>
                      <th>Diasp. Size</th>
                      <th>Verified</th>
                      <th>Note</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>004 - Team John</td>
                      <td>{{ date('d M Y') }}</td>
                      <td>12</td>
                      <td>1</td>
                      <td class="d-flex justify-content-center align-items-center">
                        <input type="checkbox" name="verified[]" value="1" class="form-check-input row-check">
                      </td>                        
                      <td><textarea name="note[]" class="form-control" rows="1"></textarea></td>
                  </tr>
              </tbody>
            </table>
          </div>    
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>