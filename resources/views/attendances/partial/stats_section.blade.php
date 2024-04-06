@if (isset($attendance))
    <div class="row mb-2" data-repeater-list="attendance_items">
        @foreach ($attendance->items as $row)
            <div class="col-md-12 col-12 my-1 stat-group" data-repeater-item>
                <fieldset class="border rounded-3 p-3">
                    {{-- <legend class="float-none w-auto px-1 fs-5"></legend> --}}
                    <div class="row mb-2">
                        <div class="col-md-3 col-12">
                            <label for="region">Region<span class="text-danger">*</span></label>
                            <select name="region_id" id="region" class="form-select" data-placeholder="Choose Region" required>
                                <option value=""></option>
                                @foreach ($attendance->regions as $region)
                                    <option value="{{ $region->id }}" {{ $region->id == $row->region_id? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="cohort">Cohort<span class="text-danger">*</span></label>
                            <select name="cohort_id" id="cohort" class="form-select" data-placeholder="Choose Cohort" required>
                                <option value=""></option>
                                @foreach ($attendance->cohorts as $cohort)
                                    <option value="{{ $cohort->id }}" {{ $cohort->id == $row->cohort_id? 'selected' : '' }}>{{ $cohort->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="age_group">Age Group<span class="text-danger">*</span></label>
                            <select name="age_group_id" id="age-group" class="form-select" data-placeholder="Choose Age-group" required>
                                <option value=""></option>
                                @foreach ($age_groups as $agegroup)
                                    <option value="{{ $agegroup->id }}" {{ $agegroup->id == $row->age_group_id? 'selected' : '' }}>{{ $agegroup->bracket }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="disability">Disability</label>
                            <select name="disability_id" id="disability" class="form-select" data-placeholder="Choose Disability">
                                <option value=""></option>
                                @foreach ($disabilities as $disability)
                                    <option value="{{ $disability->id }}" {{ $disability->id == $row->disability_id? 'selected' : '' }}>{{ $disability->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-6 col-12">
                            <label for="no_participants">Participant Stats<span class="text-danger">*</span></label>
                            <div class="row g-0">
                                <div class="col-md-4">
                                    {{ Form::text('male', $row->male, ['class' => 'form-control male', 'placeholder' => 'MALE', 'required' => 'required']) }}
                                </div>
                                <div class="col-md-4">
                                    {{ Form::text('female', $row->female, ['class' => 'form-control female', 'placeholder' => 'FEMALE', 'required' => 'required']) }}
                                </div>
                                <div class="col-md-4">
                                    {{ Form::text('total', $row->total, ['class' => 'form-control total', 'placeholder' => 'TOTAL', 'readonly' => 'readonly']) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <button type="button" class="btn btn-danger float-end" data-repeater-delete>Delete</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        @endforeach
    </div>
@else
    <div class="row mb-2" data-repeater-list="attendance_items">
        <div class="col-md-12 col-12 my-1 stat-group" data-repeater-item>
            <fieldset class="border rounded-3 p-3">
                {{-- <legend class="float-none w-auto px-1 fs-5"></legend> --}}
                <div class="row mb-2">
                    <div class="col-md-3 col-12">
                        <label for="region">Region<span class="text-danger">*</span></label>
                        <select name="region_id" id="region" class="form-select" data-placeholder="Choose Region" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="cohort">Cohort<span class="text-danger">*</span></label>
                        <select name="cohort_id" id="cohort" class="form-select" data-placeholder="Choose Cohort" required>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="age_group">Age Group<span class="text-danger">*</span></label>
                        <select name="age_group_id" id="age-group" class="form-select" data-placeholder="Choose Age-group" required>
                            <option value=""></option>
                            @foreach ($age_groups as $row)
                                <option value="{{ $row->id }}">{{ $row->bracket }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="disability">Disability</label>
                        <select name="disability_id" id="disability" class="form-select" data-placeholder="Choose Disability">
                            <option value=""></option>
                            @foreach ($disabilities as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-6 col-12">
                        <label for="no_participants">Participant Stats<span class="text-danger">*</span></label>
                        <div class="row g-0">
                            <div class="col-md-4">
                                {{ Form::text('male', null, ['class' => 'form-control male', 'placeholder' => 'MALE', 'required' => 'required']) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::text('female', null, ['class' => 'form-control female', 'placeholder' => 'FEMALE', 'required' => 'required']) }}
                            </div>
                            <div class="col-md-4">
                                {{ Form::text('total', null, ['class' => 'form-control total', 'placeholder' => 'TOTAL', 'readonly' => 'readonly']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <button type="button" class="btn btn-danger float-end" data-repeater-delete>Delete</button>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
@endif
<div class="row mb-3">
    <div class="col-md-12 col-12">
        <span class="badge bg-success text-white add-row" role="button" data-repeater-create>
            <i class="bi bi-plus-lg"></i> Row
        </span>
    </div>
</div>
