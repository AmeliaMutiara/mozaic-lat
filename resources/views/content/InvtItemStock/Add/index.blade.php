@extends('adminlte::page')

@section('title', 'PBF | Koperasi Menjangan Enam')
<link rel="stylesheet" href="{{ asset('resoursces/assets/logo_pbf.ico')}}" />
@section('js')
@stop
@section('content_header')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Barang</a></li>
    </ol>
</nav>

@stop
@section('content')

<h3 class="page-title">
    Form Tambah Barang
</h3>
<br/>
@if(session('msg'))
<div class="alert alert-info" role="alert">
    {{session('msg')}}
</div>
@endif
@if(count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</div>
@endif
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah
        </h5>
    </div>
        <button onclick="location.href='{{route('IU.index')}}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left">Kembali</i></button>
    </div>

    <form method="post" action="{{route('IU.add-process')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kode Barang Satuan <a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="item_unit_code" id="item_unit_code" value=""/>
                    </div>
                </div>
                <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Barang Satuan <a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="item_unit_name" id="item_unit_name" value=""/>
                    </div>
                </div>
                </div>
                <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Default Quantity <a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="item_unit_default_quantity" id="item_unit_default_quantity" value=""/>
                    </div>
                </div>
                </div>
                <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Keterangan <a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="item_unit_remark" id="item_unit_remark" value=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger" onclick="window.location.reload();"><i class="fa fa-times">Batal</i></button>
                <button type="submit" name="Save" class="btn btn-primary" title="Save"><i class="fa fa-check"></i></button>
            </div>
        </div>
    </div>
    </div>
</form>

@stop

@section('footer')

@stop

@section('css')

@stop