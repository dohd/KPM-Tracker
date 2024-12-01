@extends('layouts.core')
@section('title', 'General Settings')
    
@section('content')
    @include('settings.header')
    {{ Form::open(['route' => 'config.general_settings.post', 'method' => 'POST', 'class' => 'form']) }}
        <!-- Company Details -->
        <div class="card mb-3 leaders_retreat">
            <div class="card-body mb-0 pb-0">
                <h5 class="card-title">Company Details</h5>
                <div class="card-content p-2">
                    <div class="row mb-3">
                        <label for="company-name" class="col-md-2">Company Name</label>
                        <div class="col-md-8 col-12">
                            {{ Form::text('name', @$company->name, ['class' => 'form-control', 'placeholder' => 'Company Name']) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="company-email" class="col-md-2">Email</label>
                        <div class="col-md-8 col-12">
                            {{ Form::text('email', @$company->email, ['class' => 'form-control', 'placeholder' => 'Company Email']) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="company-phone" class="col-md-2">Telephone</label>
                        <div class="col-md-8 col-12">
                            {{ Form::text('phone', @$company->phone, ['class' => 'form-control', 'placeholder' => 'Telephone']) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="company-address" class="col-md-2">Address</label>
                        <div class="col-md-8 col-12">
                            {{ Form::text('address', @$company->address, ['class' => 'form-control', 'placeholder' => 'Address']) }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="company-address" class="col-md-2">Start of Fiscal Month</label>
                        <div class="col-md-8 col-12">
                            {{ Form::date('fiscal_month_start', @$company->fiscal_month_start, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Report Settings -->
        <div class="card mb-3 online_meeting">
            <div class="card-body mb-0 pb-0">
                <h5 class="card-title">Report Setting</h5>
                <div class="card-content p-2">
                    <div class="row mb-3">
                        <div class="col-md-12 col-12">
                            <div class="row">
                                <label for="performance-report-limit" class="col-md-2">
                                    Team Captains Performance Summary Access From Month
                                </label>
                                <div class="col-md-8 col-12">
                                    {{ Form::date('pfmance_report_start', @$company->pfmance_report_start, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-content p-2">
                    <div class="text-center mt-3">
                        <hr>
                        <a href="{{ route('config.general_settings') }}" class="btn btn-secondary">Cancel</a>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
@stop
