<script>
    // ========= templates =========
    const $masterTpl  = $('#masterMembersTbl').find('tr[temp="1"]').clone();
    const $monthTpl   = $('#teamSizeTbl').find('tr.month-row[temp="1"]').clone();
    const $confirmTpl = $('#teamSizeTbl').find('tr.confirm-row[temp="1"]').clone();
    $('#masterMembersTbl').find('tr[temp="1"]').remove();
    $('#teamSizeTbl').find('tr.month-row[temp="1"]').remove();
    $('#teamSizeTbl').find('tr.confirm-row[temp="1"]').remove();

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
            const id = $(this).find('.master-id').val();
            const name = ($(this).find('.master-name').val() || '').trim();
            const cat  = ($(this).find('.master-category').val() || 'local');
            if (name.length) list.push({ id, name, cat, idx });
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
            const key = $confirmRow.attr('data-row-key');
            const id = `m_${key}_${m.idx}`;
            const checked = selected.has(m.name) ? 'checked' : '';
            html += `
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="form-check border rounded px-3 py-2 h-100">
                        <input name="checked_${key}[]" class="form-check-input member-check" type="checkbox"
                            id="${id}" value="${escapeHtml(m.id)}" data-cat="${escapeHtml(m.cat)}" ${checked}>
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

        const verifyMembers = @json(@$team->verify_members ?? []);
        const teamSizes = @json(@$team->team_sizes ?? []);
        // for new team trigger default row
        if (!verifyMembers.length && !teamSizes.length) {
            $('#addMonthRow').trigger('click');
        }

        const opened = new Set();
        // map date value -> monthRow
        const rowsByDate = {};
        $('#teamSizeTbl tbody tr.month-row').each(function () {
          const $row = $(this);
          const date = $row.find('input[type="date"]').val(); // assuming 1 date input per row
          if (date) rowsByDate[date] = $row;
        });
        verifyMembers.forEach(v => {
          const $monthRow = rowsByDate[v.date];
          if (!$monthRow) return;

          const $confirmRow = $monthRow.next();

          // open once per date
          if (!opened.has(v.date)) {
            opened.add(v.date);
            $monthRow.find('.toggle-confirm').trigger('click');
          }

          $confirmRow
            .find(`.member-check[value="${v.team_member_id}"]`)
            .prop('checked', true);
            
            recalcMonth($monthRow);
        });
    }
</script>
