@extends('layouts.core')
@section('title', 'Performance Report')

@section('content')
<div class="pagetitle">
    <div class="row">
      <div class="col-6">
        <h1>Performance Report</h1>
      </div>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('assign_scores.index') }}">Performance Report</a></li>
      </ol>
    </nav>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Team Performance</h5>
        <div class="card-content p-2">
            {{ Form::open(['route' => 'reports.generate_performance', 'method' => 'POST', 'target' => '_blank']) }}
                <div class="row mb-3">
                    <label for="date" class="col-md-2">From Date</label>
                    <div class="col-md-6 col-12">
                        {{ Form::date('date_from', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="date" class="col-md-2">To Date</label>
                    <div class="col-md-6 col-12">
                        {{ Form::date('date_to', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="row mb-3 g-0">
                    <label for="output" class="col-md-2">Output Format</label>
                    <div class="col-md-4 col-12">
                        <select name="output" id="output" class="form-control">
                            <option value="pdf_print">PDF Preview</option>
                            <option value="pdf">PDF Download</option>
                            <option value="csv">CSV / Excel</option>
                        </select>  
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="text-center">
                            {{ Form::submit('Generate', ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
