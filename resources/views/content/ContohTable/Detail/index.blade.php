@extends('adminlte::page')

@section('title', "MOZAIC Parent Table")

{{-- @section('content_header')

Dashboard

@stop --}}

@section('content')

<h3 class="page-title">
    <b>Daftar Parent</b> <small>Kelola Parent </small>
</h3>
<br/>
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
          Detail Parent
      </h5>
      <div class="form-actions float-right">
          <a href='{{route('contoh.index')}}' name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-arrow-left"></i> Kembali</a>
      </div>
    </div>
  
      <div class="card-body">
        <div class="row mb-5">
            <div class="row col-6">
                <div class="row mb-3">
                    <div class="col-3">
                        <a class="text-dark col-form-label">Nama Parent</a>
                    </div>
                    <div class="col-auto">
                        :
                    </div>
                    <div class="col-8">
                        <input class="form-control input-bb" id="name" name="name" autocomplete="off" value="{{$parent->name}}" readonly/>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">
                        <a class="text-dark col-form-label">Deskripsi Parent</a>
                    </div>
                    <div class="col-auto">
                        :
                    </div>
                    <div class="col-8">
                        <input class="form-control input-bb" id="description" name="description" autocomplete="off" value="{{$parent->description}}" readonly/>
                    </div>
                </div>
            </div>
        </div>
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