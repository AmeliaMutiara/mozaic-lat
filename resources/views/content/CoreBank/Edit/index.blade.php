@extends('adminlte::page')

@section('title', 'MOZAIC Practice')

@section('content_header')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">Daftar Bank</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Ubah Bank</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    Form Ubah Bank
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

</div>
    <div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Ubah
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ route('bank.index') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{ route('bank.edit-process') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Kode Bank<a class='red'> *</a></a>
                        <input class="form-control input-bb" name="bank_code" id="bank_code" type="text" autocomplete="off" value="{{ $data['bank_code'] }}"/>
                        <input class="form-control input-bb" name="bank_id" id="bank_id" type="text" autocomplete="off" value="{{ $data['bank_id'] }}" hidden/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Nama Bank<a class='red'> *</a></a>
                        <input class="form-control input-bb" name="bank_name" id="bank_name" type="text" autocomplete="off" value="{{ $data['bank_name'] }}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">No Rekening<a class='red'> *</a></a>
                        <input class="form-control input-bb" name="account_no" id="account_no" type="text" autocomplete="off" value="{{ $data['account_no'] }}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Perkiraan<a class='red'> *</a></a>
                        {!! Form::select('account_id', $accountlist, $data['account_id'],['class' => 'selection-search-clear select-form','name'=>'account_id','id'=>'account_id']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Atas Nama</a></a>
                        <input class="form-control input-bb" name="onbehalf" id="onbehalf" type="text" autocomplete="off" value="{{ $data['onbehalf'] }}"/>
                    </div>
                </div>
                <div class="col-md-8 mt-3">
                    <div class="form-group">
                        <a class="text-dark">Keterangan</a></a>
                        <input class="form-control input-bb" name="bank_remark" id="bank_remark" type="text" autocomplete="off" value="{{ $data['bank_remark'] }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger" onclick="window.location.reload();"><i class="fa fa-times"></i> Batal</button>
                <button type="button" onclick="$(this).addClass('disabled');$('form').submit();" name="Save" class="btn btn-success" title="Save"><i class="fa fa-check"></i> Simpan</button>
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