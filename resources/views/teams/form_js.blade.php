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
        const key = $confirmRow.attr('data-row-key');

        // 1) Build a map of existing cards by member id (preserves state + events)
        const existing = {};
        $grid.find('input.member-check').each(function () {
            const id = $(this).val(); // member id
            existing[id] = $(this).closest('.col-12').detach(); // detach keeps element "as is"
        });

        // 2) Rebuild grid in the correct order, reusing old elements if present
        const frag = $(document.createDocumentFragment());

        members.forEach(m => {
            const memberId = String(m.id);

            if (existing[memberId]) {
              // reuse exact old element (state preserved)
              frag.append(existing[memberId]);
              delete existing[memberId];
              return;
            }

            // 3) Create only if it didn't exist before
            const id = `m_${key}_${m.idx}`;
            const id2 = `s_${key}_${m.idx}`;

            frag.append($(`
              <div class="col-12 col-md-6 col-lg-4">
                <div class="form-check border rounded px-3 py-2 h-100">
                  <input name="checked_${key}[]" class="form-check-input member-check" type="checkbox"
                         id="${id}" value="${escapeHtml(m.id)}" data-cat="${escapeHtml(m.cat)}">
                  <label class="form-check-label w-100" for="${id}">
                    <div class="d-flex justify-content-between">
                      <span>${escapeHtml(m.name)}</span>
                      <select id="${id2}" name="membercat_${key}[]" class="form-select form-select-sm member-category"
                              style="height:30px;width:110px;">
                        <option value="${escapeHtml(m.id)}-local"    ${m.cat === 'local' ? 'selected' : ''}>Local</option>
                        <option value="${escapeHtml(m.id)}-diaspora" ${m.cat === 'diaspora' ? 'selected' : ''}>Diaspora</option>
                        <option value="${escapeHtml(m.id)}-dormant"  ${m.cat === 'dormant' ? 'selected' : ''}>Dormant</option>
                      </select>
                    </div>
                  </label>
                </div>
              </div>
            `));
        });

        // 4) Anything left in `existing` was removed from master list → discard it
        // (detached nodes not appended will be GC’d)

        // 5) Replace grid content with preserved/new nodes
        $grid.empty().append(frag);
    }

    // ========= recalc counts based on checked members =========
    function recalcMonth($monthRow) {
        const $confirmRow = getConfirmRow($monthRow);
        let local = 0, diaspora = 0, dormant = 0, confirmed = 0;

        $confirmRow.find('select.member-category').each(function(){
            const $wrap = $(this).closest('.form-check'); // no tag restriction
            const $checkbox = $wrap.find('.member-check').first();
            if (!$checkbox.length) return;

            if ($checkbox.prop('checked')) {
                confirmed++;
                const cat = $(this).val();
                if (cat.includes('local')) local++;
                if (cat.includes('diaspora')) diaspora++;
                if (cat.includes('dormant')) dormant++;
            }
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
        $('#masterMembersTbl tbody').prepend($row);

        // re-render all month checkbox grids
        {{-- $('#teamSizeTbl tbody tr.confirm-row').not('[temp="1"]').each(function(){
            renderMonthCheckboxes($(this));
        }); --}}
    });

    $(document).on('click', '.del-master', function(){
        $(this).closest('tr').remove();
    });

    // ========= MONTH: add/remove rows (paired with confirm panel) =========
    $('#addMonthRow').on('click', function(){
        const key = nextMonthKey();
        const $newMonth = $monthTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);
        const $newConfirm = $confirmTpl.clone(true, true).removeClass('d-none').removeAttr('temp').attr('data-row-key', key);

        $('#teamSizeTbl tbody').prepend($newConfirm).prepend($newMonth);

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

    // ========= select changes  =========
    $(document).on('change', 'select.member-category', function(){
        const $wrap = $(this).closest('.form-check'); // no tag restriction
        const $checkbox = $wrap.find('.member-check').first();
        let cat = '';
        if ($(this).val().includes('local')) cat = 'local';
        if ($(this).val().includes('diaspora')) cat = 'diaspora';
        if ($(this).val().includes('dormant')) cat = 'dormant';
        $checkbox.attr('data-cat', cat);
    });

    // ========= init =========
    // Edit Mode
    // ========================
    const team = @json(@$team);
    if (team?.id) {
        const teamMembers = @json($team->members ?? collect());        
        const teamSizes = @json($team->team_sizes ?? collect());
        const verifyMembers = @json($team->verify_members ?? collect());

        // for new team trigger default row
        if (!verifyMembers.length && !teamSizes.length) {
            $('#addMonthRow').trigger('click');
        }
        if (!teamMembers.length) {
            $('#addMasterMember').trigger('click');
        }

        if (verifyMembers.length) {
            // map date value -> monthRow
            const rowsByDate = {};
            $('#teamSizeTbl tbody tr.month-row').each(function () {
                const $row = $(this);
                const date = $row.find('input[type="date"]').val(); // assuming 1 date input per row
                if (date) rowsByDate[date] = $row;
            });

            const opened = new Set();
            verifyMembers.forEach((v,i) => {
              const $monthRow = rowsByDate[v.date];
              if (!$monthRow) return;

              const $confirmRow = $monthRow.next();

              // open once per date
              if (!opened.has(v.date)) {
                opened.add(v.date);
                $monthRow.find('.toggle-confirm').trigger('click');
              }

              const $checkbox = $confirmRow.find(`.member-check[value="${v.team_member_id}"]`);
              const $select = $checkbox.closest('.form-check').find('select.member-category').first();

              if ($checkbox.length) {
                $checkbox.prop('checked', true);
                $select.val(v.team_member_id + '-' + v.category);
              }         
            });
            

            for (const key in rowsByDate) {
                const $monthRow = rowsByDate[key]; 
                // recompute confirmed checkboxes
                setTimeout(() => recalcMonth($monthRow), 0);
                // hide open confirm panels
                $monthRow.next().find('.collapse-confirm').trigger('click');

                // disable non-editable rows
                const $confirmRow = $monthRow.next();
                for (let i = 0; i < teamSizes.length; i++) {
                    const teamSize = teamSizes[i];
                    if (teamSize.start_period === key && !teamSize.is_editable) {
                        $monthRow.find('.toggle-confirm').prop('disabled', true);
                        $monthRow.find('input').each(function(){
                            $(this)
                                .css({ pointerEvents: 'none', backgroundColor: '#e9ecef' })
                                .off('click')
                                .on('click', function(e) {
                                    e.preventDefault(); // stops toggling
                                });
                        });
                        break;
                    }
                }
            }          
        }

        // correct rendering bug
        setTimeout(() => {
            $('input[data-value]').each(function() {
                $(this).val($(this).attr('data-value'));
            });            
        }, 100);
    } else {
        $('#addMasterMember').trigger('click');
        $('#addMonthRow').trigger('click');
    }
</script>
