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

<h3 class="page-title">
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
    <form method=""></form>
</div>