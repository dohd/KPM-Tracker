<div class="row mb-3">
    <label for="full_name" class="col-md-2">First Name<span class="text-danger">*</span></label>
    <div class="col-md-6 col-12">
        {{ Form::text('fname', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="name" class="col-md-2">Last Name<span class="text-danger">*</span></label>
    <div class="col-md-6 col-12">
        {{ Form::text('lname', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="email" class="col-md-2">Email<span class="text-danger">*</span></label>
    <div class="col-md-6 col-12">
        {{ Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="phone" class="col-md-2">Telephone<span class="text-danger">*</span></label>
    <div class="col-md-6 col-12">
        {{ Form::text('phone', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="user_type" class="col-md-2">Role<span class="text-danger">*</span></label>
    <div class="col-md-6 col-12">
        <select name="user_type" id="user_type" class="form-control select2" data-placeholder="Choose Role" autocomplete="false" required>
            @foreach (['chair', 'captain', 'member'] as $item)
                @if (@$user_profile->user_type)
                    <option value="{{ $item }}" {{ $user_profile->user_type == $item? 'selected' : '' }}>{{ ucfirst($item) }}</option>
                @else
                    <option value="{{ $item }}" {{  $item == 'member'? 'selected' : '' }}>{{ ucfirst($item) }}</option>
                @endif
            @endforeach
        </select>   
    </div>
</div>
<div class="row mb-3">
    <label for="user_type" class="col-md-2">Team</label>
    <div class="col-md-6 col-12">
        <select name="team_id" id="team" class="form-control select2" data-placeholder="Choose Team" autocomplete="false">
            <option value=""></option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}" {{ @$user_profile->team_id == $team->id? 'selected' : '' }}>
                    {{ tidCode('', $team->tid) }} - {{ $team->name }}
                </option>
            @endforeach
        </select>   
    </div>
</div>
