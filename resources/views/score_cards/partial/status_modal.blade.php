<!-- Modal -->
<div class="modal fade" id="status_modal" tabindex="-1" aria-labelledby="status_modal_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="status_modal_label">Scale Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::model((object)[], ['route' => array('score_cards.update', 0), 'method' => 'PATCH', 'id' => 'status-form'])}}
                <div class="modal-body">
                    <div class="row">
                        <label for="status" class="col-md-2 col-form-label">Status</label>
                        <div class="col-md-10 col-10">
                            <select name="is_active" id="status" class="form-select">
                                @foreach (['Active' => 1, 'Inactive' => 0] as $key => $value)
                                    <option value="{{ $value }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
