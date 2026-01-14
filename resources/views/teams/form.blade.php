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

<div style="width:85%; margin-left:auto; margin-right:auto">

    {{-- ========= MASTER REGISTER TABLE ========= --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0"><i class="bi bi-people"></i> Master Member Register</h6>
        <button type="button" class="btn btn-sm btn-outline-success" id="addMasterMember">
            <i class="bi bi-person-plus"></i> Add Member
        </button>
    </div>

    <div class="table-responsive mb-4">
        <table id="masterMembersTbl" class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:45%">Member Name</th>
                    <th style="width:20%">Default Category</th>
                    <th style="width:25%">Notes</th>
                    <th style="width:10%">Action</th>
                </tr>
            </thead>
            <tbody>
                {{-- row template --}}
                <tr class="d-none" temp="1">
                    <td>
                        <input type="text" class="form-control form-control-sm master-name" placeholder="e.g. John Mwangi" required>
                        <input type="hidden" class="master-id" value="">
                    </td>
                    <td>
                        <select class="form-select form-select-sm master-category">
                            <option value="local">Local</option>
                            <option value="diaspora">Diaspora</option>
                            <option value="dormant">Dormant</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm master-notes" placeholder="Optional">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger del-master">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <small class="text-muted">
            Master list. Monthly confirmations will show these members as checkboxes.
        </small>
    </div>

    {{-- ========= MONTHLY CONFIRMATION TABLE ========= --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Monthly Confirmation</h6>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addMonthRow">
            <i class="bi bi-plus-circle"></i> Add Month Row
        </button>
    </div>

    <div class="table-responsive">
        <table id="teamSizeTbl" class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>Beginning Date</th>
                    <th>Local Size</th>
                    <th>Diaspora Size</th>
                    <th>Dormant Size</th>
                    <th width="22%">Action</th>
                </tr>
            </thead>
            <tbody>

                {{-- Existing month rows (kept from your original logic) --}}
                @if (@$team)
                    @foreach ($team->team_sizes->sortByDesc('start_period') as $row)
                        @php
                            $isLocked = ($row->in_score && auth()->user()->user_type != 'chair');
                        @endphp

                        <tr class="month-row" data-row-key="{{ $loop->index }}">
                            <td>
                                <input type="date" name="start_date[]" value="{{ $row->start_period }}" class="form-control" {{ $isLocked ? 'readonly' : '' }}>

                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-confirm">
                                        <i class="bi bi-ui-checks"></i> Confirm Members
                                    </button>
                                    <span class="badge bg-light text-dark align-self-center confirm-summary">
                                        Confirmed: <span class="sum-confirmed">0</span> |
                                        Local: <span class="sum-local">{{ (int)$row->local_size }}</span> |
                                        Diaspora: <span class="sum-diaspora">{{ (int)$row->diaspora_size }}</span> |
                                        Dormant: <span class="sum-dormant">{{ (int)$row->dormant_size }}</span>
                                    </span>
                                </div>
                            </td>

                            <td><input type="number" name="local_size[]" value="{{ $row->local_size }}" class="form-control local-size" {{ $isLocked ? 'readonly' : '' }}></td>
                            <td><input type="number" name="diaspora_size[]" value="{{ $row->diaspora_size }}" class="form-control diaspora-size" {{ $isLocked ? 'readonly' : '' }}></td>
                            <td><input type="number" name="dormant_size[]" value="{{ $row->dormant_size }}" class="form-control dormant-size" {{ $isLocked ? 'readonly' : '' }}></td>

                            <td>
                                <button type="button" class="btn btn-outline-primary add-month-row"><i class="bi bi-plus-circle"></i></button>
                                <button type="button" class="btn btn-outline-danger del-month-row" {{ $isLocked ? 'disabled' : '' }}><i class="bi bi-dash-circle"></i></button>
                            </td>
                        </tr>

                        {{-- checkbox panel --}}
                        <tr class="confirm-row d-none" data-row-key="{{ $loop->index }}">
                            <td colspan="5">
                                <div class="border rounded p-3 bg-white">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                        <div class="fw-semibold">
                                            <i class="bi bi-check2-square"></i> Confirm Members (tick from master)
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary select-all" {{ $isLocked ? 'disabled' : '' }}>
                                                <i class="bi bi-check-all"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary clear-all" {{ $isLocked ? 'disabled' : '' }}>
                                                <i class="bi bi-x-circle"></i> Clear
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary collapse-confirm">
                                                <i class="bi bi-chevron-up"></i> Hide
                                            </button>
                                        </div>
                                    </div>

                                    {{-- where checkboxes render --}}
                                    <div class="row g-2 member-checkbox-grid"></div>

                                    <small class="text-muted d-block mt-2">
                                        Counts auto-calc from selected members using their default category in the master register.
                                    </small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif

                {{-- month row template --}}
                <tr class="d-none month-row" temp="1" data-row-key="__KEY__">
                    <td>
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
                    <td>
                        <button type="button" class="btn btn-outline-primary add-month-row"><i class="bi bi-plus-circle"></i></button>
                        <button type="button" class="btn btn-outline-danger del-month-row"><i class="bi bi-dash-circle"></i></button>
                    </td>
                </tr>

                {{-- confirm row template --}}
                <tr class="d-none confirm-row" temp="1" data-row-key="__KEY__">
                    <td colspan="5">
                        <div class="border rounded p-3 bg-white">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <div class="fw-semibold">
                                    <i class="bi bi-check2-square"></i> Confirm Members (tick from master)
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

                            <div class="row g-2 member-checkbox-grid"></div>

                            <small class="text-muted d-block mt-2">
                                Counts auto-calc from selected members using their default category in the master register.
                            </small>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

@section('script')
<script>
    // ========= templates =========
    const $masterTpl  = $('#masterMembersTbl').find('tr[temp="1"]');
    const $monthTpl   = $('#teamSizeTbl').find('tr.month-row[temp="1"]');
    const $confirmTpl = $('#teamSizeTbl').find('tr.confirm-row[temp="1"]');

    function nextMonthKey() {
        return $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').length + 1;
    }

    function getConfirmRow($monthRow) {
        const key = $monthRow.attr('data-row-key');
        return $('#teamSizeTbl tbody tr.confirm-row').not('[temp="1"]').filter(`[data-row-key="${key}"]`);
    }

    // ========= master list (source of truth) =========
    function getMasterMembers() {
        const list = [];
        $('#masterMembersTbl tbody tr').not('[temp="1"]').each(function(idx){
            const name = ($(this).find('.master-name').val() || '').trim();
            const cat  = ($(this).find('.master-category').val() || 'local');
            if (name.length) list.push({ name, cat, idx });
        });
        return list;
    }

    function escapeHtml(str) {
        return String(str)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    // ========= render checkbox grid per month =========
    function renderMonthCheckboxes($confirmRow) {
        const $grid = $confirmRow.find('.member-checkbox-grid');
        const members = getMasterMembers();

        // preserve currently selected values
        const selected = new Set();
        $confirmRow.find('input.member-check:checked').each(function(){
            selected.add($(this).val());
        });

        let html = '';
        members.forEach(m => {
            const id = `m_${$confirmRow.attr('data-row-key')}_${m.idx}`;
            const checked = selected.has(m.name) ? 'checked' : '';
            html += `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-check border rounded px-3 py-2 h-100">
                        <input class="form-check-input member-check" type="checkbox"
                            id="${id}" value="${escapeHtml(m.name)}" data-cat="${escapeHtml(m.cat)}" ${checked}>
                        <label class="form-check-label w-100" for="${id}">
                            <div class="d-flex justify-content-between">
                                <span>${escapeHtml(m.name)}</span>
                                <small class="text-muted text-uppercase">${escapeHtml(m.cat)}</small>
                            </div>
                        </label>
                    </div>
                </div>`;
        });

        $grid.html(html);
    }

    // ========= recalc counts based on checked members =========
    function recalcMonth($monthRow) {
        const $confirmRow = getConfirmRow($monthRow);
        let local = 0, diaspora = 0, dormant = 0, confirmed = 0;

        $confirmRow.find('input.member-check:checked').each(function(){
            confirmed++;
            const cat = ($(this).data('cat') || '').toLowerCase();
            if (cat === 'local') local++;
            if (cat === 'diaspora') diaspora++;
            if (cat === 'dormant') dormant++;
        });

        $monthRow.find('.local-size').val(local);
        $monthRow.find('.diaspora-size').val(diaspora);
        $monthRow.find('.dormant-size').val(dormant);

        $monthRow.find('.sum-confirmed').text(confirmed);
        $monthRow.find('.sum-local').text(local);
        $monthRow.find('.sum-diaspora').text(diaspora);
        $monthRow.find('.sum-dormant').text(dormant);
    }

    // ========= MASTER: add/remove =========
    $('#addMasterMember').on('click', function(){
        const $row = $masterTpl.clone(true, true).removeClass('d-none').removeAttr('temp');
        $('#masterMembersTbl tbody').append($row);

        // re-render all month checkbox grids
        $('#teamSizeTbl tbody tr.confirm-row').not('[temp="1"]').each(function(){
            renderMonthCheckboxes($(this));
        });
    });

    $(document).on('click', '.del-master', function(){
        $(this).closest('tr').remove();

        // re-render all month checkbox grids + recalc
        $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').each(function(){
            const $month = $(this);
            const $confirm = getConfirmRow($month);
            renderMonthCheckboxes($confirm);
            recalcMonth($month);
        });
    });

    $(document).on('keyup change', '.master-name, .master-category', function(){
        // re-render all month checkbox grids + recalc
        $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').each(function(){
            const $month = $(this);
            const $confirm = getConfirmRow($month);
            renderMonthCheckboxes($confirm);
            recalcMonth($month);
        });
    });

    // ========= MONTH: add/remove rows (paired with confirm panel) =========
    $('#addMonthRow').on('click', function(){
        const key = nextMonthKey();
        const $newMonth = $monthTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);
        const $newConfirm = $confirmTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);

        $('#teamSizeTbl tbody').append($newMonth).append($newConfirm);

        renderMonthCheckboxes($newConfirm);
        recalcMonth($newMonth);
    });

    $(document).on('click', '.add-month-row, .del-month-row', function(){
        const $monthRow = $(this).closest('tr.month-row');

        if ($(this).hasClass('add-month-row')) {
            const key = nextMonthKey();
            const $newMonth = $monthTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);
            const $newConfirm = $confirmTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);

            $monthRow.before($newConfirm);
            $newConfirm.before($newMonth);

            renderMonthCheckboxes($newConfirm);
            recalcMonth($newMonth);
        } else {
            const $confirmRow = getConfirmRow($monthRow);
            if (!$monthRow.prev().length && $monthRow.next().attr('temp')) return;
            $confirmRow.remove();
            $monthRow.remove();
        }
    });

    // ========= toggle confirm panel =========
    $(document).on('click', '.toggle-confirm', function(){
        const $monthRow = $(this).closest('tr.month-row');
        const $confirmRow = getConfirmRow($monthRow);

        // ensure grid is rendered (in case master changed)
        renderMonthCheckboxes($confirmRow);

        $confirmRow.toggleClass('d-none');
    });

    $(document).on('click', '.collapse-confirm', function(){
        $(this).closest('tr.confirm-row').addClass('d-none');
    });

    // ========= select/clear all =========
    $(document).on('click', '.select-all', function(){
        const $confirmRow = $(this).closest('tr.confirm-row');
        $confirmRow.find('input.member-check').prop('checked', true);

        const key = $confirmRow.attr('data-row-key');
        const $monthRow = $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').filter(`[data-row-key="${key}"]`);
        recalcMonth($monthRow);
    });

    $(document).on('click', '.clear-all', function(){
        const $confirmRow = $(this).closest('tr.confirm-row');
        $confirmRow.find('input.member-check').prop('checked', false);

        const key = $confirmRow.attr('data-row-key');
        const $monthRow = $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').filter(`[data-row-key="${key}"]`);
        recalcMonth($monthRow);
    });

    // ========= checkbox changes recalc =========
    $(document).on('change', 'input.member-check', function(){
        const $confirmRow = $(this).closest('tr.confirm-row');
        const key = $confirmRow.attr('data-row-key');
        const $monthRow = $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').filter(`[data-row-key="${key}"]`);
        recalcMonth($monthRow);
    });

    // ========= init =========
    const team = @json(@$team);
    if (!team) {
        $('#addMasterMember').trigger('click');
        $('#addMonthRow').trigger('click');
    } else {
        // for existing rows, render checkbox grids (empty selections unless you later bind saved selections)
        $('#teamSizeTbl tbody tr.month-row').not('[temp="1"]').each(function(){
            const $month = $(this);
            const $confirm = getConfirmRow($month);
            renderMonthCheckboxes($confirm);

            // keep the summary showing existing numeric values
            $month.find('.sum-local').text(parseInt($month.find('.local-size').val() || 0));
            $month.find('.sum-diaspora').text(parseInt($month.find('.diaspora-size').val() || 0));
            $month.find('.sum-dormant').text(parseInt($month.find('.dormant-size').val() || 0));
            $month.find('.sum-confirmed').text(
                parseInt($month.find('.local-size').val() || 0) +
                parseInt($month.find('.diaspora-size').val() || 0) +
                parseInt($month.find('.dormant-size').val() || 0)
            );
        });
    }
</script>
@stop
