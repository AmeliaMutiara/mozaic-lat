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
{{-- Filter --}}
<div id="accordion">
    <form  method="post" action="" enctype="multipart/form-data">
    @csrf
        <div class="card border border-dark">
        <div class="card-header bg-dark" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <h5 class="mb-0">
                Filter
            </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row ">
                    <div class = "col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Mulai
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" type="text" name="start_date" id="start_date" value="{{ $start_date ?? date('Y-m-d')}}" style="width: 15rem;"/>
                        </div>
                    </div>

                    <div class = "col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Akhir
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" type="text" name="end_date" id="end_date" value="{{ $end_date ?? date('Y-m-d')}}" style="width: 15rem;"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <a href="" type="reset" name="Reset" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
                    <button type="submit" name="Find" class="btn btn-primary" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
        </div>
    </form>
</div>

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
          <a href='' name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah Data</a>
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