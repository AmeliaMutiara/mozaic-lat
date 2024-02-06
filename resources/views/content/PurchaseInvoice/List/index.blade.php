@inject('PurchaseInvoice','App\Http\Controllers\PurchaseInvoiceController')
@extends('adminlte::page')

@section('title', 'MOZAIC Practice')
@section('js')
<script>
    function reset_add(){
		$.ajax({
				type: "GET",
				url : "{{route('filter-reset-purchase-invoice')}}",
				success: function(msg){
                    location.reload();
			}
		});
	}
    $('#unpay').hide();
    $('#notaModal').on('show.bs.modal', function (event) {
        $(this).find('#unpay').hide();
        var button = $(event.relatedTarget)
        var purchase_invoice_id = button.data('pid')
        var credit = button.data('credit')
        $(this).find('#acceptance').removeClass('disabled');
        $(this).find('#expenditure').removeClass('disabled');
        $(this).find('#acceptance').prop('disabled',false);
        $(this).find('#expenditure').prop('disabled',false);
        if(credit&&credit!=''){
            $(this).find('#unpay').show();
            $(this).find('#expenditure').addClass('disabled');
            $(this).find('#expenditure').prop('disabled',true);
        }
        $(this).find('#note').attr('href', "{{route('purchase-note')}}"+'/'+purchase_invoice_id)
        $(this).find('#acceptance').attr('href', "{{route('print-proof-acceptance-item')}}"+'/'+purchase_invoice_id)
        $(this).find('#expenditure').attr('href', "{{route('print-proof-expenditure-cash')}}"+'/'+purchase_invoice_id)
        $(this).find('#purchase').attr('href', "{{route('print-proof-purchase')}}"+'/'+purchase_invoice_id)
    });
</script>
@stop
@section('content_header')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Pembelian</li>
    </ol>
  </nav>

@stop

@section('content')

<h3 class="page-title">
    <b>Daftar Pembelian</b> <small>Kelola Pembelian </small>
</h3>
<br/>
<div id="accordion">
    <form  method="post" action="{{ route('filter-purchase-invoice') }}" enctype="multipart/form-data">
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
                    <button type="reset" name="Reset" class="btn btn-danger" onclick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" name="Find" class="btn btn-primary" title="Search Data"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </div>
        </div>
    </form>
</div>
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
        <button onclick="location.href='{{ route('/purchase-invoice/add') }}'" name="Find" class="btn btn-sm btn-info" title="Add Data"><i class="fa fa-plus"></i> Tambah Pembelian </button>
    </div>
  </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="example" style="width:100%" class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                    <tr>
                        <th style="text-align: center">No </th>
                        <th style="text-align: center">No Pembelian</th>
                        <th style="text-align: center">Tanggal Pembelian</th>
                        <th style="text-align: center">Nama Supplier</th>
                        <th style="text-align: center">Nama Gudang</th>
                        <th style="text-align: center">Jumlah Pembelian</th>
                        <th style="text-align: center">Dibayar</th>
                        <th style="text-align: center">Hutang</th>
                        <th style="text-align: center">Jumlah Retur</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                   @foreach ($data as $row )
                   <tr>
                        <td class="text-center">{{ $no++ }}.</td>
                        <td>{{ $row['purchase_invoice_no'] }}</td>
                        <td>{{ date('d-m-Y', strtotime($row['purchase_invoice_date'])) }}</td>
                        <td>{{ $PurchaseInvoice->getSupplierName($row['supplier_id']) }}</td>
                        <td>{{ $PurchaseInvoice->getWarehouseName($row['warehouse_id']) }}</td>
                        <td style="text-align: right">{{ number_format($row['total_amount'],2,',','.') }}</td>
                        <td style="text-align: right">{{ number_format($row['paid_amount'],2,',','.') }}</td>
                        <td style="text-align: right">{{ number_format($row['owing_amount'],2,',','.') }}</td>
                        <td style="text-align: right">{{ number_format((empty($row['return_amount'])?0:$row['return_amount']),2,',','.') }}</td>
                        <td class="text-center">
                            @if ($row['data_state'] == 0)
                                <a type="button" class="btn btn-outline-danger btn-sm" href="{{ route('/purchase-invoice/delete/'.$row['purchase_invoice_id']) }}">Hapus</a>
                            @else
                                Dihapus
                            @endif
                        </td>
                        <td class="text-center">
                            <a type="button" class="btn btn-outline-info btn-sm" href="{{ route('/purchase-invoice/detail/'.$row['purchase_invoice_id']) }}">Detail</a>
                            @if ($row['data_state'] == 0)
                            <a type="button" class="btn btn-outline-warning btn-sm" href="{{ route('pi-edd',$row['purchase_invoice_id']) }}">Edit Tgl</a>
                            <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-pid="{{$row['purchase_invoice_id']}}" data-credit="{{($row['purchase_payment_method']&&(($row['owing_amount']??0)!=0))}}" data-target="#notaModal">Nota </button>
                            @endif
                        </td>
                   </tr>
                   @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="notaModal" tabindex="-1" aria-labelledby="notaModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="notaModalLabel">Cetak Nota</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row justify-content-center">
            <a id="purchase" class="btn btn-secondary col-auto mx-1 mb-1" href="#"><i class="fa fa-file-pdf"></i> Bukti Pembelian</a>
            <a id="expenditure" class="btn btn-dark col-auto mx-1 mb-1" href="#"><i class="fa fa-file-pdf"></i> Bukti Pengeluaran</a>
            <a id="acceptance" class="btn btn-darker col-auto mx-1 mb-1" href="#"><i class="fa fa-file-pdf"></i> Bukti Penerimaan</a>
            <a id="note" class="btn btn-primary col-auto mx-1 mb-1" href="#">Nota</a>
        </div>
        <div class="row justify-content-center" id="unpay">
            <small class="col text-center text-muted">*Harap Melalukan Pembayaran Hutang Untuk Mencetak Bukti</small>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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