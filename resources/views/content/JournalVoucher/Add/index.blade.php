@inject('JournalVoucher','App\Http\Controllers\JournalVoucherController')
@extends('adminlte::page')

@section('title', 'MOZAIC Practice')
@section('js')
<script>
      function function_elements_add(name, value){
        console.log("name " + name);
        console.log("value " + value);
		$.ajax({
				type: "POST",
				url : "{{route('jv.add-elements')}}",
				data : {
                    'name'      : name, 
                    'value'     : value,
                    '_token'    : '{{csrf_token()}}'
                },
				success: function(msg){
			}
		});
	}

    function processAddArrayJournalVoucher(){
        var account_id                  = document.getElementById("account_id").value;
        var account_status              = document.getElementById("account_status").value;
        var journal_voucher_amount      = document.getElementById("journal_voucher_amount").value;
       
        $.ajax({
            type: "POST",
            url : "{{route('jv.add-array')}}",
            data: {
                'account_id'                : account_id,
                'account_status'            : account_status,
                'journal_voucher_amount'    : journal_voucher_amount,
                '_token'                    : '{{csrf_token()}}'
            },
            success: function(msg){
                location.reload();
            }
        });
    }
   
    function reset_add(){
		$.ajax({
				type: "GET",
				url : "{{route('jv.add-reset')}}",
				success: function(msg){
                    location.reload();
			}

		});
	}
    function check(){
        i = 9;
        date = $('#journal_voucher_date').val();
        console.log(moment(date).format('YYYYMM')<moment().format('YYYYMM'));
        console.log(moment(date).format('YYYYMM'));
        console.log(moment().format('YYYYMM'));
        $('#confirmModal').modal({backdrop: 'static', keyboard: false});
        $('#confirmModal').modal('show');
        if(moment(date).format('YYYYMM')<moment().format('YYYYMM')){
            $('#h5-modal').html('Pastikan Tanggal sudah benar!');
            $('#modal-content').html('Jurnal ini akan dibuat di bulan yang berbeda. Proses ini akan memakan waktu pastikan tidak ada tansaksi yang dijalankan dan tidak menutup tab ini sebelum proses selesai!!');
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
            $('#h5-modal').html('Pastikan Tanggal sudah benar!');
            $('#modal-content').html('Anda yakin tanggal jurnal sudah benar?');
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
@section('content_header')
    
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jv.index') }}">Daftar Jurnal Umum</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Jurnal Umum</li>
    </ol>
  </nav>

@stop

@section('content')
  <!-- Modal -->
  <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Tambah Jurnal Umum</h5>
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
          <button type="button" id="reverse-journal" onclick="$(this).prop('disabled',true);$('form').submit();" class="btn row btn-danger">Ya</button>
        </div>
      </div>
    </div>
  </div>
  
<h3 class="page-title">
    Form Tambah Jurnal Umum
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
            Form Tambah
        </h5>
        <div class="float-right">
            <button onclick="location.href='{{ route('jv.index') }}'" name="Find" class="btn btn-sm btn-info" title="Back"><i class="fa fa-angle-left"></i>  Kembali</button>
        </div>
    </div>

    <form method="post" action="{{ route('jv.add-process') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-9">
                    <div class="form-group">
                        <a class="text-dark">Tanggal<a class='red'> *</a></a>
                        <input style="width: 30%" class="form-control input-bb" name="journal_voucher_date" id="journal_voucher_date" type="date" data-date-format="dd-mm-yyyy" autocomplete="off" onchange="function_elements_add(this.name, this.value)" value="{{ ($journal['journal_voucher_date'] ??'') ? ($journal['journal_voucher_date'] ??'') : date('Y-m-d') }}"/>
                    </div>
                </div>
                <div class="col-md-9 mb-5">
                    <div class="form-group">
                        <a class="text-dark">Uraian</a>
                        <textarea rows="2" cols="2" style="width: 60%" class="form-control input-bb" name="journal_voucher_description" id="journal_voucher_description" autocomplete="off" onchange="function_elements_add(this.name, this.value)">{{ $journal['journal_voucher_description'] ??''}}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">No. Perkiraan<a class='red'> *</a></a>
                        {!! Form::select('account_id',  $account, 0, ['class' => 'form-control selection-search-clear select-form', 'id' => 'account_id']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">D/K<a class='red'> *</a></a>
                        {!! Form::select(0,  $status, 0, ['class' => 'form-control selection-search-clear select-form', 'id' => 'account_status']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <a class="text-dark">Jumlah<a class='red'> *</a></a>
                        <input type="number" class="form-control input-bb text-right" id="journal_voucher_amount" autocomplete="off" value=""/>
                    </div>
                </div>
            </div>
        </div> 
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <a onclick="processAddArrayJournalVoucher()" class="btn btn-success" title="Add">Tambah</a>
            </div>
        </div>       
</div>

<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Daftar
        </h5>
    </div>
        <div class="card-body">
            <div class="form-body form">
                <div class="table-responsive">
                    <table class="table table-bordered table-advance table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style='text-align:center; width: 20%'>No. Perkiraan</th>
                                <th style='text-align:center; width: 20%'>Nama Perkiraan</th>
                                <th style='text-align:center; width: 20%'>Debit</th>
                                <th style='text-align:center; width: 20%'>Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!is_array($arraydata)){
                                    echo "<tr><th colspan='4' style='text-align  : center !important;'>Data Kosong</th></tr>";
                                } else {
                                    foreach ($arraydata AS $key => $val){
                                        if ($val['account_status'] == 0) {
                                            echo"
                                                <tr>
                                                    <td style='text-align  : left !important;'>".$JournalVoucher->getAccountCode($val['account_id'])." - ".$JournalVoucher->getAccountName($val['account_id'])."</td>
                                                    <td style='text-align  : left !important;'>".$JournalVoucher->getStatus($val['account_status'])."</td>
                                                    <td style='text-align  : right !important;'>".number_format($val['journal_voucher_amount'],2,'.',',')."</td>
                                                    <td style='text-align  : right !important;'></td>";
                                        }else {
                                            echo"
                                                <tr>
                                                    <td style='text-align  : left !important;'>".$JournalVoucher->getAccountCode($val['account_id'])." - ".$JournalVoucher->getAccountName($val['account_id'])."</td>
                                                    <td style='text-align  : left !important;'>".$JournalVoucher->getStatus($val['account_status'])."</td>
                                                    <td style='text-align  : right !important;'></td>
                                                    <td style='text-align  : right !important;'>".number_format($val['journal_voucher_amount'],2,'.',',')."</td>";
                                        }?>
                                        <input type="text" name="account_id" value="{{ $val['account_id'] }}" hidden>
                                        <input type="text" name="account_status" value="{{ $val['account_status'] }}" hidden>
                                        <input type="text" name="journal_voucher_amount" value="{{ $val['journal_voucher_amount'] }}" hidden>
                                        <?php
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="form-actions float-right">
                <button type="reset" name="Reset" class="btn btn-danger" onclick="reset_add();"><i class="fa fa-times"></i> Batal</button>
                <button type="button" onclick="check()" name="Save" class="btn btn-success" title="Save"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </div>
    </form>
</div>
</div>



@stop

@section('footer')
    
@stop

@section('css')
    
@stop