@extends('adminlte::page')

@section('title', 'MOZAIC Practice')

@section('js')

<script>
    function function_elements_add(name, value) {
        console.log("name " + name);
        console.log("value " + value);
        $.ajax({
            type: "POST",
            url : "{{ route('ic.add-elements') }}",
            data : {
                'name'      : name,
                'value'     : value,
                '_token'    : '{{ csrf_token() }}'
            },
            success: function(msg) {
            }
        });
    }

    function reset_add() {
        $.ajax({
            type: "GET",
            url : "{{ route('ic.add-reset') }}",
            success: function(msg){
                location.reload();
            }
        });
    }

    function margin_limit(value) {
        if (value > 100) {
            alert('Margin Tidak Boleh Melebihi 100%');
            $('#margin_percentage').val('');
        }
    }
</script>

@stop

@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ic.index') }}">Daftar Kategori</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Kategori</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    Form Tambah Kategori Barang
</h3>
<br>

@if (session('msg'))
<div class="alert alert-{{session('type')??'info'}}" role="alert">
    {{ session('msg') }}
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</div>
@endif

<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Tambah
        </h5>
        <div class="float-right">
            <a href='{{ route('ic.index') }}' name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"> Kembali</i></a>
        </div>
    </div>

    <form action="{{ route('ic.add-process') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Kode Kategori Barang<a class="red"> *</a></a>
                        <input type="text" class="form-control input-bb" name="item_category_code" id="category_code" autocomplete="off" onchange="function_elements_add(this.name, this.value);" value="{{ $datacategory['item_category_code'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Nama Kategori Barang<a class="red"> *</a></a>
                        <input type="text" class="form-control input-bb" name="item_category_name" id="category_name" autocomplete="off" onchange="function_elements_add(this.name, this.value);" value="{{ $datacategory['item_category_name'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <a class="text-dark">Margin Kategori Barang (%)</a></a>
                        <input type="number" class="form-control input-bb" name="margin_percentage" id="margin_percentage" autocomplete="off" onchange="function_elements_add(this.name, this.value);" value="{{ $datacategory['margin_percentage'] ?? '' }}" oninput="margin_limit(this.value)">
                    </div>
                </div>
                <div class="col-md-8 mt-3">
                    <div class="form-group">
                        <a class="text-dark">Keterangan</a>
                        <textarea class="form-control input-bb" name="item_category_remark" id="category_remark" type="text" autocomplete="off" onchange="functions_elements_add(this.name, this.value);">{{ $datacategory['item_category_remark'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger" onclick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="button" onclick="$(this).addClass('disabled');$('form').submit();" name="Save" class="btn btn-success" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>

@stop

@section('footer')

@stop

@section('css')

@stop