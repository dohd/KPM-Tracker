<div class="row mb-3">
    <label for="name" class="col-md-2">Team Name</label>
    <div class="col-md-8 col-12">
        {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="member_list" class="col-md-2">List of Members</label>
    <div class="col-md-8 col-12">
        {{ Form::textarea('member_list', null, ['class' => 'form-control', 'rows' => '1', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="team_size" class="col-md-2">Local Team Size</label>
    <div class="col-md-8 col-12">
        {{ Form::number('total', null, ['class' => 'form-control', 'placeholder' => 'No. of local team members', 'required' => 'required']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="team_size" class="col-md-2">Diaspora Team Size</label>
    <div class="col-md-8 col-12">
        {{ Form::number('diasp_total', null, ['class' => 'form-control', 'placeholder' => 'No. of diaspora team members']) }}
    </div>
</div>
<div class="row mb-3">
    <label for="guest" class="col-md-2">Max Guest Size</label>
    <div class="col-md-8 col-12">
        {{ Form::number('max_guest', null, ['class' => 'form-control', 'placeholder' => 'No. of maximum guest members', 'required' => 'required']) }}
    </div>
</div>
