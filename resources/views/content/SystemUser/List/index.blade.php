@inject('SystemUser', 'App\Http\Controllers\SystemUserController')

@extends('adminlte::page')

@section('title', 'MOZAIC Practice')

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar System User</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    <b>Daftar System User</b> <small>Mengelola System User </small>
</h3>
<br/>

@if(session('msg'))
<div class="alert alert-info" role="alert">
    {{session('msg')}}
</div>
@endif 
<div class="card border border-dark">
  <div class="card-header bg-dark clearfix">
    <h5 class="mb-0 float-left">
        Daftar
    </h5>
    <div class="form-actions float-right">
        <button onclick="location.href='{{ route('systemuser.add') }}'" name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah System User Baru</button>
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