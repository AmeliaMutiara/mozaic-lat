@inject('JournalMemorial', 'App\Http\Controllers\AcctJournalMemorialController')
@extends('adminlte::page')

@section('title', "MOZAIC Practice")
@section('js')
<script>
var d = {!! json_encode(session('msg')) !!}
if(d){
    $('#modalDeleted').modal('show');
}
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
    <form method="post" action="{{route('jm.filter')}}" enctype="multipart/form-data">
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
                            <input style="width: 50%" class="form-control input-bb" name="start_date" id="start_date" type="date" data-date-format="dd-mm-yy" autocomplete="off" onchange="function_elements_add(this.name, this.value)" value="{{ $start_date}}"/>  
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-md-line-input">
                            <section class="control-label">Tanggal Akhir
                                <span class="required text-danger">
                                    *
                                </span>
                            </section>
                            <input style="width: 50%" class="form-control input-bb" name="end_date" id="end_date" type="date" data-date-format="dd-mm-yy" autocomplete="off" onchange="function_elements_add(this.name, this.value)" value="{{ $start_date}}"/>  
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <a href="{{ route('jm.filter-reset')}}" type="reset" name="Reset" class="btn btn-danger"><i class="fa fa-times"></i>Batal</a>
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
                        <th width="15%" class="no-sort" style="vertical-align: middle;text-align:center;">Nominal</th>
                        <th width="15%" class="no-sort" style="vertical-align: middle;text-align:center;">D/K</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $no = 1;
                    $totaldebet = 0;
                    $totalkredit = 0;
                @endphp
                @if (count($data) == 0)
                    <tr>
                        <td colspan="9" style="text-align: center">Data Kosong</td>
                    </tr>
                @else
                    @php
                        $id = 0;
                    @endphp
                    @foreach ($data as $val)
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
                                        {{ $val['transaction_module_code']??'' }}</td>
                                    <td style="text-align:left; background-color:lightgrey">
                                        {{ $val['journal_voucher_description'] }}</td>
                                    <td style="text-align:center; background-color:lightgrey">
                                        {{ date('d-m-Y', strtotime($val['journal_voucher_date'])) }}</td>
                                    <td style="text-align:left; background-color:lightgrey">
                                        {{ $row->account->account_code }}</td>
                                    <td style="text-align:left; background-color:lightgrey">
                                        {{ $row->account->account_name }}</td>
                                    <td style="text-align:center; background-color:lightgrey">
                                        {{ number_format($nominal,2,'.',',') }}
                                    </td>
                                    <td style="text-align:center; background-color:lightgrey">
                                        {{ $status }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td style="text-align:center"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $row->account->account_code }}</td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $row->account->account_name }}</td>
                                    <td style="text-align:center;">{{ number_format($nominal, 2) }}</td>
                                    <td style="text-align:center;">{{ $status }}</td>
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
                    <?php
                        // $no = 1;
                        // $total_debit = 0;
                        // $total_credit = 0;
                        // if(empty($data)){
                        //     echo "
                        //         <tr>
                        //             <td colspan='8' align='center'>Data Kosong</td>
                        //         </tr>
                        //         ";
                        // } else{
                        //     foreach ($data as $key => $val) {
                        //         $id = $JournalMemorial->getMinID($val['journal_voucher_id']);

                        //             if ($val['journal_voucher_debit_amount'] <> 0) {
                        //                 $nominal = $val['journal_voucher_debit_amount'];
                        //                 $status = 'D';
                        //             } else if ($val['journal_voucher_credit_amount'] <> 0){
                        //                 $nominal = $val['journal_voucher_credit_amount'];
                        //                 $status = 'K';
                        //             } else {
                        //                 $nominal = 0;
                        //                 $status = 'Kosong';
                        //             }

                        //         if ($val['journal_voucher_item_id'] == $id) {
                        //             $delete = '<td> </td>';
                        //             $now = Carbon\Carbon::now()->format('Y-m');
                        //             if ($val['reverse_state']==0&&(Auth::id()=='55'||Auth::id()==58||Auth::id()==61)&&$now==Carbon\Carbon::parse($val['journal_voucher_date'])->format('Y-m')) {
                        //             $delete = "
                        //             <td>
                        //             <a type='button' class='btn my-3 btn-outline-danger btn-sm' href='".route('reverse-journal-memorial',['journal_voucher_id'=>$val['journal_voucher_id']])."' onclick='".'return confirm("Apakah Anda Yakin Menghapus Data Ini ? ")'."'>Hapus</a>
                        //             </td>";}
                        //             echo"
                        //                 <tr class='table-active'>
                        //                     <td style='text-align:center'>$no.</td>
                        //                     <td>".$val['transaction_module_code']."</td>
                        //                     <td>".$val['journal_voucher_description']."</td>
                        //                     <td>".date('d-m-Y', strtotime($val['journal_voucher_date']))."</td>
                        //                     <td>".$JournalMemorial->getAccountCode($val['account_id'])."</td>
                        //                     <td>".$JournalMemorial->getAccountName($val['account_id'])."</td>
                        //                     <td style='text-align: right'>".number_format($nominal,2,'.',',')."</td>
                        //                     <td style='text-align: right'>".$status."</td>
                        //             ";
                        //             $no++;
                        //         } else{
                        //             echo "
                        //                 <tr>
                        //                     <td style='text-align:center'></td>
                        //                     <td></td>
                        //                     <td></td>
                        //                     <td></td>
                        //                     <td> ".$JournalMemorial->getAccountCode($val['account_id'])."</td>
                        //                     <td> ".$JournalMemorial->getAccountName($val['account_id'])."</td>
                        //                     <td style='text-align: right'>".number_format($nominal,2,'.',',')."</td>
                        //                     <td style='text-align: right'>".$status."</td>
                        //                 </tr>
                        //             ";
                        //         }
                        //         $total_debit += $val['journal_voucher_debit_amount'];
                        //         $total_credit += $val['journal_voucher_credit_amount'];
                                
                        //     }
                        // }
                        ?>
                </tbody>
                <tfoot class="ui-state-default">
                <tr class="ui-state-default">
                    <td style="text-align: right;font-weight:bold;" colspan="6">Total Debit</td>
                    <td style="text-align: right">{{ number_format($totaldebet,2,'.',',')}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: right;font-weight:bold;" colspan="6">Total Kredit</td>
                    <td style="text-align: right">{{ number_format($totalkredit,2,'.',',')}}</td>
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