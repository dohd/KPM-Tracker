{{-- ======== BASIC TEAM DETAILS ========= --}}
<div class="row mb-3">
    <label for="is_active" class="col-md-2">Is Active</label>
    <div class="col-md-8 col-12">
        {{ Form::checkbox('is_active', isset($team->is_active)? $team->is_active : 1, true, ['id' => 'is_active']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Team Name</label>
    <div class="col-md-8 col-12">
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="guest" class="col-md-2">Max Guest Size</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_guest', null, ['class' => 'form-control', 'placeholder' => 'No. of maximum guest members', 'required' => 'required']) }}
    </div>
</div>

<div class="mt-2 mb-3" style="width:85%; margin-left:auto; margin-right:auto">
    {{-- ========= MASTER TABLE ========= --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0"><i class="bi bi-people"></i> Master Member Register</h6>
        <button type="button" class="btn btn-sm btn-outline-success" id="addMasterMember">
            <i class="bi bi-person-plus"></i> Add Member
        </button>
    </div>

    <div class="table-responsive mb-4" style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd;">
        <table id="masterMembersTbl" class="table table-bordered table-sm align-middle">
            <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                    <th>Member Name</th>
                    <th>Category</th>
                    <th>DF Name</th>
                    <th>Phone No.</th>
                    <th>Physical Addr.</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- row template --}}
                <tr class="d-none" temp="1">
                    <td>
                        <input type="text" name="full_name[]" class="form-control form-control-sm master-name" placeholder="e.g. John Doe">
                        <input type="hidden"  class="master-id" name="master_id[]" value="0">
                    </td>
                    <td>
                        <select 
                            name="category[]" 
                            class="form-select form-select-sm master-category"
                        >
                            @foreach (['local', 'diaspora', 'dormant'] as $value)
                                <option value="{{ $value }}">{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="df_name[]" class="form-control form-control-sm master-dfname">
                    </td>
                    <td>
                        <input type="text" name="phone_no[]" class="form-control form-control-sm master-phone">
                    </td>
                    <td>
                        <input type="text" name="physical_addr[]" class="form-control form-control-sm master-addr">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger del-master">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @foreach (($team->members ?? collect())->sortByDesc('id') as $key => $row)
                    <tr>
                        <td>
                            <input type="text" name="full_name[]" value="{{ $row->full_name }}" class="form-control form-control-sm master-name" placeholder="e.g. John Doe">
                            <input type="hidden"  class="master-id" name="master_id[]" value="{{ $row->id }}">
                        </td>
                        <td>
                            @if ($row->is_category_member_verified)
                                <select 
                                    name="category[]" 
                                    class="form-select form-select-sm master-category"
                                    readonly
                                    style="pointer-events: none; background-color: #e9ecef;" 
                                >
                                    @foreach (['local', 'diaspora', 'dormant'] as $value)
                                        <option value="{{ $value }}" {{ $value === $row->category? 'selected' : '' }}>{{ ucfirst($value) }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="category[]" class="form-select form-select-sm master-category">                                
                                    @foreach (['local', 'diaspora', 'dormant'] as $value)
                                        <option value="{{ $value }}" {{ $value === $row->category? 'selected' : '' }}>{{ ucfirst($value) }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </td>
                        <td>
                            <input type="text" name="df_name[]" value="{{ $row->df_name }}" class="form-control form-control-sm master-dfname">
                        </td>
                        <td>
                            <input type="text" name="phone_no[]" value="{{ $row->phone_no }}" class="form-control form-control-sm master-phone">
                        </td>
                        <td>
                            <input type="text" name="physical_addr[]" value="{{ $row->physical_addr }}" class="form-control form-control-sm master-addr">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger del-master" {{ $row->is_category_member_verified? 'disabled' : '' }}>
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- ======== END MASTER TABLE ======== --}}

    {{-- ========= MONTHLY CONFIRMATION TABLE ========= --}}
    @isset($team)
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Monthly Confirmation</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addMonthRow">
                <i class="bi bi-plus-circle"></i> Add Month Row
            </button>
        </div>

        <div class="table-responsive" style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd;">
            <table id="teamSizeTbl" class="table table-bordered table-sm align-middle">
                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                    <tr>
                        <th>Beginning Date</th>
                        <th width="20%">Local Size</th>
                        <th width="20%">Diaspora Size</th>
                        <th width="20%">Dormant Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Existing month rows (kept from your original logic) --}}
                    @foreach (($team->team_sizes ?? collect())->sortByDesc('start_period') as $row)
                        <tr class="month-row" data-row-key="{{ $loop->index }}">
                            <td>
                                <input type="hidden" name="team_size_id[]" value="{{ $row->id }}">
                                <input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control" {{ $row->is_editable? '' :  'readonly' }}>
                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-confirm">
                                        <i class="bi bi-ui-checks"></i> Confirm Members
                                    </button>
                                    <span class="badge bg-light text-dark align-self-center confirm-summary">
                                        Confirmed: <span class="sum-confirmed">0</span> |
                                        Local: <span class="sum-local">{{ (int) $row->local_size }}</span> |
                                        Diaspora: <span class="sum-diaspora">{{ (int) $row->diaspora_size }}</span> |
                                        Dormant: <span class="sum-dormant">{{ (int) $row->dormant_size }}</span>
                                    </span>
                                </div>
                            </td>
                            <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" data-value="{{$row->local_size}}" class="form-control local-size"></td>
                            <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" data-value="{{$row->diaspora_size}}" class="form-control diaspora-size"></td>
                            <td><input type="number" name="dormant_size[]" value="{{ $row->dormant_size }}" data-value="{{$row->dormant_size}}" class="form-control dormant-size"></td>
                            <td class="text-center">                                
                                <button type="button" class="btn btn-sm btn-outline-danger del-month-row" {{ $row->is_editable? '' : 'disabled' }}><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>

                        {{-- checkbox panel --}}
                        <tr class="confirm-row d-none" data-row-key="{{ $loop->index }}">
                            <td colspan="5">
                                <div class="border rounded p-3 bg-white">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                        <div class="fw-semibold">
                                            <i class="bi bi-check2-square"></i> Confirm Members 
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary select-all" {{ $row->is_editable? '' : 'disabled' }}>
                                                <i class="bi bi-check-all"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary clear-all" {{ $row->is_editable? '' : 'disabled' }}>
                                                <i class="bi bi-x-circle"></i> Clear
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary collapse-confirm">
                                                <i class="bi bi-chevron-up"></i> Hide
                                            </button>
                                        </div>
                                    </div>

                                    {{-- where checkboxes render --}}
                                    <div class="row g-3 member-checkbox-grid"></div>                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    {{-- month row template --}}
                    <tr class="d-none month-row" temp="1" data-row-key="__KEY__">
                        <td>
                            <input type="hidden" name="team_size_id[]" value="">
                            <input type="date" name="start_date[]" value="" class="form-control">
                            <div class="mt-2 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary toggle-confirm">
                                    <i class="bi bi-ui-checks"></i> Confirm Members
                                </button>
                                <span class="badge bg-light text-dark align-self-center confirm-summary">
                                    Confirmed: <span class="sum-confirmed">0</span> |
                                    Local: <span class="sum-local">0</span> |
                                    Diaspora: <span class="sum-diaspora">0</span> |
                                    Dormant: <span class="sum-dormant">0</span>
                                </span>
                            </div>                                
                        </td>
                        <td><input type="number" name="local_size[]" value="0" class="form-control local-size"></td>
                        <td><input type="number" name="diaspora_size[]" value="0" class="form-control diaspora-size"></td>
                        <td><input type="number" name="dormant_size[]" value="0" class="form-control dormant-size"></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger del-month-row"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    {{-- confirm row template --}}
                    <tr class="d-none confirm-row" temp="1" data-row-key="__KEY__">
                        <td colspan="5">
                            <div class="border rounded p-3 bg-white">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <div class="fw-semibold">
                                        <i class="bi bi-check2-square"></i> Confirm Members
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary select-all">
                                            <i class="bi bi-check-all"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary clear-all">
                                            <i class="bi bi-x-circle"></i> Clear
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary collapse-confirm">
                                            <i class="bi bi-chevron-up"></i> Hide
                                        </button>
                                    </div>
                                </div>
                                <div class="row g-3 member-checkbox-grid"></div>                            
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endisset
    {{-- ======== END MONTHLY CONFIRMATION TABLE ======== --}}
</div>

@section('script')
@include('teams.form_js')
@stop
