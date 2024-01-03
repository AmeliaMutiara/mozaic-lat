@extends('adminlte::page')

@section('title', "MOZAIC Parent Table")

{{-- @section('content_header')

Dashboard

@stop --}}

@section('content')

<h3 class="page-title">
    <b>Daftar Kategori Barang </b> <small>Kelola Kategori Barang</small>
</h3>
<br/>
{{-- Filter --}}

@if (session('msg'))
<div class="alert alert-{{session('type')??'info'}}" role="alert">
    {{ session('msg') }}
</div>
@endif
@if (count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach ($errors->all() as $error)
        {{ $error }}
    @endforeach
</div>
@endif

<div class="card border border-dark">
    <div class="card-header bg-dark clearfix">
      <h5 class="mb-0 float-left">
          Daftar
      </h5>
      <div class="form-actions float-right">
          <a href='{{ route('ic.add') }}' name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah Data</a>
      </div>
    </div>
  
      <div class="card-body">
          <div class="table-responsive">
              <!--begin::Table-->
              {{ $dataTable->table() }}
              <!--end::Table-->
               {{-- Inject Scripts --}}
            @push('scripts')
                {{ $dataTable->scripts() }}
            @endpush
          </div>
      </div>
    </div>
  </div>

@stop

@section('css')

@stop

@section('js')

@stop