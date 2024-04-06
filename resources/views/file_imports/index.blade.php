@extends('layouts.core')

@section('title', 'File Import Management')
    
@section('content')
    @include('file_imports.header')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                {{ Form::open(['route' => 'file_imports.store', 'method' => 'POST', 'files' => true, 'class' => 'form']) }}
                    <div class="row mb-2 p-2">
                        <div class="col-md-6 col-12 mt-2 mb-3">
                            @php
                                $categories = [
                                    'self_advocates' => 'Self-Advocates (Rightholder List)',
                                    'families' => 'Families (Rightholder List)',
                                    'support_groups' => 'Support Groups (Rightholder List)',
                                ];
                            @endphp
                            <select name="category" id="category" class="custom-control col-12" required>
                                <option value="">-- Select Template Category --</option>
                                @foreach ($categories as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 bg-light pt-3 mb-2">
                            <p>
                                Data format should be as per downloaded template. 
                                <a href="#" class="ms-1 dn-link" download><u><b>Click here to download</b></u></a>
                            </p>
                        </div>
                        <hr style="border: none; border-bottom: 2px solid black;">
                        
                        <div class="col-md-6 col-12">
                            <label class="form-label" for="file">Import File</label>
                            {{ Form::file('file', ['class' => 'form-control', 'id' => 'file', 'accept' => '.xls, .xlsx', 'required' => 'required' ]) }}
                        </div>
                        <div class="col-md-2 col-12 pt-4">
                            <button type="submit" class="btn btn-primary mt-2"><i class="bi bi-upload"></i> Import</button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@section('script')
<script>
    $('#category').change(function() {
        // default anchor link
        if (this.value) {
            $('.dn-link').attr('href', "{{ asset('storage/import_templates') }}/" + this.value  + '.xls');
        } else {
            $('.dn-link').attr('href', '#');
        }  
        
        // template rules
        $('.dn-link').parents('p').next().remove();
        if (this.value == 'families' || this.value == 'self_advocates') {
            const el = '<p><span class="text-danger">*</span> <b><i>Column4 - DOB must be Fomarted as General Text instead of Date</i></b></p>';
            $('.dn-link').parents('p').after(el);
        }      
    }).trigger('change');
</script>
@stop

