@inject('SystemUser', 'App\Http\Controllers\SystemUserController')

@extends('adminlte::page')

@section('title', 'MOZAIC Practice')
@section('js')
    <script>
        var table;
        $(document).ready(function(){
            table =  $('#myDataTable').DataTable({
     
             "processing": true,
             "serverSide": true,
             "pageLength": 5,
             "lengthMenu": [ [5, 15, 20, 10000], [5, 15, 20, "All"] ],
             "order": [[3, 'asc']],
             "ajax": "{{ url('data-table-item') }}",
             "columns":[
                {data: 'no'},
                {data: 'item_category_name'},
                {data: 'item_code'},
                {data: 'item_name'},
                {data: 'barcode'},
                {data: 'action'},
             ],
        
             });
        });
    </script>
@endsection
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Barang</li>
    </ol>
  </nav>

@stop

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
        <button onclick="location.href='{{ route('item.add') }}'" name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah Barang </button>
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

@section('footer')
    
@stop

@section('css')
    
@stop

@section('js')
    
@stop