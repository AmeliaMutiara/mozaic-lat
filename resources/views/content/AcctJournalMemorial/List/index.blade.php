@inject('JournalMemorial', 'App\Http\Controllers\AcctJournalMemorialController')
@extends('adminlte::page')

@section('title', "MOZAIC Practice")
@section('js')
<script>
var d = {!! json_encode(session('msg')) !!}
if(d){
    $('#modalDeleted').modal('show');
}
$(document).ready(function(){
            table =  $('#tabel-journal').DataTable({
            //  "processing": true,
             "serverSide": true,
             "lengthMenu": [ [18446744073709551610 ,5, 15, 25,50, 100], ["ALL",5, 15, 25,50, 100] ],
             "order": [[3, 'asc']],
             "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
                },
                {
                'target': 7,
                'visible': false,
                'searchable': false
                }
            ],
             "ajax": "{{ route('jm.table') }}",
             footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    total=0;
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    var dk = api.column(8).data().toArray();
                    console.log(dk[1]);
                    console.log(dk);
                    d = 0;
                    k = 0;
                    api.column(7).data().each( function (i, v) {
                        if(dk[v]=="<div class='text-right'> K </div>"){
                            d += parseInt(i.replace(/[^0-9.]+/g,''));
                        }
                    });
                    api.column(7).data().each( function (i, v) {
                        if(dk[v]=="<div class='text-right'> K </div>"){
                            k += parseInt(i.replace(/[^0-9.]+/g,''));
                        }
                    });
                    // Update footer
                    $('tr:eq(0) td:eq(1)',api.table().footer()).html(toRp(d));
                    $('tr:eq(1) td:eq(1)',api.table().footer()).html(toRp(k));
                },
                columns: [
                    { data: 'no' },
                    { data: 'transaction_module_code' },
                    { data: 'journal_voucher_description' },
                    { data: 'journal_voucher_date' },
                    { data: 'account_code' },
                    { data: 'account_name' },
                    { data: 'nominal_view' },
                    { data: 'nominal' },
                    { data: 'status' }
                ]
             });
});
</script>
@endsection

@section('content_header')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Jurnal Memorial</li>
    </ol>
</nav>

@stop

@section('content')

<h3>
    <b>Daftar Jurnal Memorial</b><small>Keloal Daftar Jurnal Memorial</small>
</h3>
<br/>

<div id="accordion"> 
    <div class="modal fade" id="modalDeleted" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        arial-labelledby="modalCloseCashierLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="modalCloseCashierLabel1">Data Sudah Dihapus</h5>
                </div>
                <div class="modal-body">
                    Data Berhasil Dihapus
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <form method="post" action="{{route('filter-journal-memorial')}}" enctype="multipart/form-data">
    @csrf
        <div class="card border border-dark">
        <div class="card-header bg-dark" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <h5 class="mb-0">
                Filter
            </h5>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Awal
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input style="width: 50%" class="form-control input-bb" name="start_date" id="start_date" type="date" data-date-format="dd-mm-yy" autocomplete="off" onchange="function_elements_add(this.name, this.value)" value="{{ $start_date}}"/>  >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Akhir
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input style="width: 50%" class="form-control input-bb" name="start_date" id="start_date" type="date" data-date-format="dd-mm-yy" autocomplete="off" onchange="function_elements_add(this.name, this.value)" value="{{ $start_date}}"/>  >
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <a href="{{ route('reset-filter-journal-memorial')}}" type="reset" name="Reset" class="btn btn-danger"><i class="fa fa-times"></i>Batal</a>
                    <button type="submit" name="find" class="btn btn-primary" title="Search Data"><i class="fa fa-search"></i>Cari</button>
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
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-journal" style="width: 100%" class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                    <tr>
                        <th width="5%" class="no-sort" style="vertical-align: middle;text-align:center;">No</th>
                        <th width="10%" style="vertical-align: middle;text-align:center;">Bukti</th>
                        <th width="20%" style="vertical-align: middle;text-align:center;">Uraian</th>
                        <th width="15%" style="vertical-align: middle;text-align:center;">Tanggal</th>
                        <th width="15%" style="vertical-align: middle;text-align:center;">No. Per</th>
                        <th width="15%" class="no-sort" style="vertical-align: middle;text-align:center;">Perkiraan</th>
                        <th width="15%" class="no-sort" style="vertical-align: middle;text-align:center;">Nomianal</th>
                        <th width="15%" class="no-sort" style="vertical-align: middle;text-align:center;">Nominal_val</th>
                        <th width="10%" class="no-sort" style="vertical-align: middle;text-align:center;">D / K</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        $total_debit = 0;
                        $total_credit = 0;
                        if(empty($data)){
                            echo "
                                <tr>
                                    <td colspan='8' align='center'>Data Kosong</td>
                                </tr>
                                ";
                        } else{
                            foreach ($data as $key => $val) {
                                $id = $JournalMemorial->getMinID($val['journal_voucher_id']);

                                    if ($val['journal_voucher_debit_amount'] <> 0) {
                                        $nominal = $val['journal_voucher_debit_amount'];
                                        $status = 'D';
                                    } else if ($val['journal_voucher_credit_amount'] <> 0){
                                        $nominal = $val['journal_voucher_credit_amount'];
                                        $status = 'K';
                                    } else {
                                        $nominal = 0;
                                        $status = 'Kosong';
                                    }

                                if ($val['journal_voucher_item_id'] == $id) {
                                    $delete = '<td> </td>';
                                    $now = Carbon\Carbon::now()->format('Y-m');
                                    if ($val['reverse_state']==0&&(Auth::id()=='55'||Auth::id()==58||Auth::id()==61)&&$now==Carbon\Carbon::parse($val['journal_voucher_date'])->format('Y-m')) {
                                    $delete = "
                                    <td>
                                    <a type='button' class='btn my-3 btn-outline-danger btn-sm' href='".route('reverse-journal-memorial',['journal_voucher_id'=>$val['journal_voucher_id']])."' onclick='".'return confirm("Apakah Anda Yakin Menghapus Data Ini ? ")'."'>Hapus</a>
                                    </td>";}
                                    echo"
                                        <tr class='table-active'>
                                            <td style='text-align:center'>$no.</td>
                                            <td>".$val['transaction_module_code']."</td>
                                            <td>".$val['journal_voucher_description']."</td>
                                            <td>".date('d-m-Y', strtotime($val['journal_voucher_date']))."</td>
                                            <td>".$JournalMemorial->getAccountCode($val['account_id'])."</td>
                                            <td>".$JournalMemorial->getAccountName($val['account_id'])."</td>
                                            <td style='text-align: right'>".number_format($nominal,2,'.',',')."</td>
                                            <td style='text-align: right'>".$status."</td>
                                    ";
                                    $no++;
                                } else{
                                    echo "
                                        <tr>
                                            <td style='text-align:center'></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td> ".$JournalMemorial->getAccountCode($val['account_id'])."</td>
                                            <td> ".$JournalMemorial->getAccountName($val['account_id'])."</td>
                                            <td style='text-align: right'>".number_format($nominal,2,'.',',')."</td>
                                            <td style='text-align: right'>".$status."</td>
                                        </tr>
                                    ";
                                }
                                $total_debit += $val['journal_voucher_debit_amount'];
                                $total_credit += $val['journal_voucher_credit_amount'];
                                
                            }
                        }
                        ?>
                </tbody>
                <tfoot class="ui-state-default">
                <tr class="ui-state-default">
                    <td style="text-align: right;font-weight:bold;" colspan="6">Total Debit</td>
                    <td style="text-align: right">{{ number_format($total_debit,2,'.',',')}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: right;font-weight:bold;" colspan="6">Total Kredit</td>
                    <td style="text-align: right">{{ number_format($total_credit,2,'.',',')}}</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
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