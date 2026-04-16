<fieldset class="border rounded px-3">
  <legend class="float-none w-auto px-2 fs-6">Scoring Bands (match first ≥ Threshold %)</legend>
  <div class="table-responsive mb-2">
    <table class="table table-sm align-middle pb-0 mb-0" id="bandsTable">
      <thead>
        <tr>
          {{-- <th style="width: 180px">Threshold % (≥)</th>
          <th style="width: 140px">Points</th> --}}
          <th width="50%">Threshold % (≥)</th>
          <th width="50%">Points</th>
        </tr>
      </thead>
      <tbody>
        <!-- Pre-fill your example bands -->
        @foreach (range(1,10) as $i)
        <tr>
          <td><input type="number" class="form-control band-threshold" value="" min="0" max="100" step="0.01"></td>
          <td><input type="number" class="form-control band-points" value="" min="0" step="1"></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</fieldset>