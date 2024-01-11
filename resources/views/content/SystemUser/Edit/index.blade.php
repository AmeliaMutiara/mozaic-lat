@extends('adminlte::page')

@section('title', 'MOZAIC Minimarket')

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Daftar System User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit System User</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    Form Edit System User
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
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Edit
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ route('user.index') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{ route('user.edit-process') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        
                        <a class="text-dark">Nama<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="username" id="username" value="{{$systemuser['username']}}"/>
                        <input class="form-control input-bb" type="hidden" name="user_id" id="user_id" value="{{$user_id}}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nama Panjang<a class='red'> *</a></a>
                        <input class="form-control input-bb" type="text" name="full_name" id="full_name" value="{{$systemuser['full_name']}}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">No HP</a>
                        <input class="form-control input-bb" type="text" name="phone_number" id="phone_number" value="{{$systemuser['phone_number']}}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <a class="text-dark">User Group<a class='red'> *</a></a>
                    <br/>
                    {!! Form::select('user_group_id',  $systemusergroup, $systemuser['user_group_id'], ['class' => 'selection-search-clear select-form']) !!}
                </div>
                {{-- <div class="col-md-4">
                    <a class="text-dark">Bagian<a class='red'> *</a></a>
                    <br/>
                    {!! Form::select('section_id',  $coresection, $systemuser['section_id'], ['class' => 'selection-search-clear select-form']) !!}
                </div> --}}
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger" onClick="window.location.reload();"><i class="fa fa-times"></i> Batal</button>
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

@section('js')
    
@stop