@inject('JournalVoucher','App\Http\Controllers\JournalVoucherController')
@extends('adminlte::page')
@section('title', 'MOZAIC Practice')
@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Jurnal Umum </li>
    </ol>
  </nav>
@stop
@section('content')
<h3 class="page-title">
    <b>Daftar Jurnal Umum </b> <small>Kelola Daftar Jurnal Umum  </small>
</h3>
<br/>
<div id="accordion">
    <form  method="post" action="{{ route('jv.filter') }}" enctype="multipart/form-data">
    @csrf
        <div class="card border border-dark">
        <div class="card-header bg-dark" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <h5 class="mb-0">
                Filter
            </h5>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class = "row">
                    <div class = "col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Mulai
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" type="text" name="start_date" id="start_date" value="{{ $start_date }}" style="width: 15rem;"/>
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Akhir
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input type ="date" class="form-control form-control-inline input-medium date-picker input-date" data-date-format="dd-mm-yyyy" type="text" name="end_date" id="end_date" value="{{ $end_date }}" style="width: 15rem;"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <a href="{{ route('jv.filter-reset') }}" type="reset" name="Reset" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
                    <button type="submit" name="Find" class="btn btn-primary" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
        </div>
    </form>
</div>
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
            Daftar
        </h5>
        <div class="form-actions float-right">
            <button onclick="location.href='{{ route('jv.add') }}'" name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah Jurnal Umum </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table style="width:100%" class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 5%">No</th>
                        <th style="text-align: center;">Tanggal</th>
                        <th style="text-align: center;">Dibuat</th>
                        <th style="text-align: center;">Uraian</th>
                        <th style="text-align: center;">No. Perkiraan</th>
                        <th style="text-align: center;">Nama Perkiraan</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: center;">D/K</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $totaldebet = 0;
                        $totalkredit = 0;
                    @endphp
                    @if (count($acctmemorialjournal) == 0)
                        <tr>
                            <td colspan="8" style="text-align: center">Data Kosong</td>
                        </tr>
                    @else
                        @php
                            $id = 0;
                        @endphp
                        @foreach ($acctmemorialjournal as $val)
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($val->items as $row)
                                @php
                                    if ($row['journal_voucher_debit_amount'] != 0) {
                                        $nominal = $row['journal_voucher_debit_amount'];
                                        $status = 'D';
                                    } elseif ($row['journal_voucher_credit_amount'] != 0) {
                                        $nominal = $row['journal_voucher_credit_amount'];
                                        $status = 'K';
                                    }
                                @endphp
                                @if ($i == 1)
                                    <tr>
                                        <td style="text-align:center; background-color:lightgrey">
                                            {{ $no++ }}</td>
                                        <td style="text-align:left; background-color:lightgrey">
                                            {{ $val['transaction_module_code'] }}</td>
                                        <td style="text-align:left; background-color:lightgrey">
                                            {{ $val['journal_voucher_description'] }}</td>
                                        <td style="text-align:center; background-color:lightgrey">
                                            {{ date('d-m-Y', strtotime($val['journal_voucher_date'])) }}</td>
                                        <td style="text-align:left; background-color:lightgrey">
                                            {{ $row->account->account_code }}</td>
                                        <td style="text-align:left; background-color:lightgrey">
                                            {{ $row->account->account_name }}</td>
                                        <td style="text-align:right; background-color:lightgrey">
                                            {{ number_format($nominal, 2) }}</td>
                                        <td style="text-align:right; background-color:lightgrey">
                                            {{ $status }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="text-align:center"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td></td>
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $row->account->account_name }}</td>
                                        <td style="text-align:right;">{{ number_format($nominal, 2) }}</td>
                                        <td style="text-align:right;">{{ $status }}</td>
                                    </tr>
                                @endif
                                @php
                                    $i++;
                                    $totaldebet += $row['journal_voucher_debit_amount'];
                                    $totalkredit += $row['journal_voucher_credit_amount'];
                                    if ($id != $row['journal_voucher_id']) {
                                        $id = $row['journal_voucher_id'];
                                    }
                                @endphp
                            @endforeach
                        @endforeach
                    @endif
                    <tr>
                        <td colspan="6" align="right"><b>Total Debet</td>
                        <td align="right"><b>{{ number_format($totaldebet, 2) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><b>Totel Kredit</td>
                        <td align="right"><b>{{ number_format($totalkredit, 2) }}</b></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
  <!-- Modal -->
  <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Jurnal Balik</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h5 id="h5-modal"></h5>
          <div id="modal-content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">Batal</button>
          <a type="button" id="reverse-journal" class="btn row btn-danger">Ya</a>
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
<script>
    function check(date,url){
        i = 9;
        console.log(moment(date).format('YYYYMM')<moment().format('YYYYMM'));
        console.log(moment(date).format('YYYYMM'));
        console.log(moment().format('YYYYMM'));
        $('#confirmModal').modal({backdrop: 'static', keyboard: false});
        $('#confirmModal').modal('show');
        $('#reverse-journal').attr('href', url);
        if(moment(date).format('YYYYMM')<moment().format('YYYYMM')){
            $('#h5-modal').html('Yakin ingin menghapus jurnal ini?');
            $('#modal-content').html('Jurnal ini dibuat di bulan yang berbeda. Proses ini akan memakan waktu pastikan tidak ada tansaksi yang dijalankan dan tidak menutup tab ini sebelum proses selesai!!');
            var counter = setInterval(function() {
                $('#reverse-journal').html("Ya ("+(i--)+")");
            },1000);
            setTimeout(function() {
                clearInterval(counter);
                $('#reverse-journal').html('Ya');
                $('#reverse-journal').prop("disabled",false);
                $('#reverse-journal').removeClass("disabled");
            }, 10000);
        }else{
            $('#h5-modal').html('Yakin ingin menghapus jurnal ini?');
            $('#modal-content').html('');
            $('#reverse-journal').html('Ya');
            $('#reverse-journal').removeClass("disabled");
            $('#reverse-journal').prop("disabled",false);
        }
    }
    $(document).ready(function(){
        $('#confirmModal').on('show.bs.modal', function (event) {
            $(this).find('#reverse-journal').addClass("disabled");
            $(this).find('#reverse-journal').prop("disabled",true);
            $(this).find('#reverse-journal').html("Ya ("+(i+1)+")");
        })
        $('#confirmModal').on('hidden.bs.modal', function (event) {
            $(this).find('#reverse-journal').prop( "disabled", true );
            $(this).find('#reverse-journal').attr('href', '');
        })
    });
</script>
@stop