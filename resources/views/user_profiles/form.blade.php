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
    <label for="user_type" class="col-md-2">User Type</label>
    <div class="col-md-6 col-12">
        <select name="user_type" id="user_type" class="form-control select2" data-placeholder="Choose User Type" autocomplete="false" required>
            <option value=""></option>
            @foreach (['Admin' => 'Chairperson', 'Standard' => 'Team Lead'] as $key => $item)
                <option value="{{ $key }}" {{ @$user_profile->user_type == $key? 'selected' : '' }}>{{ $item }}</option>
            @endforeach
        </select>   
    </div>
</div>
