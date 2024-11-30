@extends('layouts.core')
@section('title', 'Score Variance Summary')

@section('content')
<div class="pagetitle">
    <div class="row">
      <div class="col-6">
        <h1>Score Variance Summary</h1>
      </div>
    </div>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('reports.score_variance') }}">Score Variance Summary</a></li>
      </ol>
    </nav>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title mb-0">Score Variance Summary</h5>
        <div class="card-content p-2">
            {{ Form::open(['route' => 'reports.score_variance.post', 'method' => 'POST', 'target' => '_blank']) }}
                <div class="row mb-3">
                    <label for="team" class="col-md-2">Team</label>
                    <div class="col-md-6 col-12">
                        <select name="team_id" id="team" class="form-control select2" data-placeholder="Choose Team" required>
                            <option value=""></option>
                            @foreach ($teams as $row)
                                <option value="{{ $row->id }}">
                                    {{ tidCode('', $row->tid) }} - {{ $row->name }}
                                </option>
                            @endforeach
                        </select>   
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="date" class="col-md-2">From Date</label>
                    <div class="col-md-6 col-12">
                        {{ Form::date('date_from', null, ['class' => 'form-control', 'required' => 'required']) }}
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="date" class="col-md-2">To Date</label>
                    <div class="col-md-6 col-12">
                        {{ Form::date('date_to', null, ['class' => 'form-control', 'required' => 'required']) }}
                    </div>
                </div>
                <div class="row mb-3 g-0">
                    <label for="output" class="col-md-2">Output Format</label>
                    <div class="col-md-4 col-12">
                        <select name="output" id="output" class="form-control">
                            <option value="pdf_print">PDF Preview</option>
                            {{-- <option value="pdf">PDF Download</option> --}}
                            {{-- <option value="csv">CSV / Excel</option> --}}
                        </select>  
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="text-center">
                            {{ Form::submit('Run Report', ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
