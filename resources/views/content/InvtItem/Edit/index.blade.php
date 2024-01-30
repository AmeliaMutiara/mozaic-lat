@extends('adminlte::page')
<?php
if (empty($items)) {
    $items['kemasan'] = 1;
    $items['max_kemasan'] = 4;
}
if (empty($invtpaket)) {
    $invtpaket = '';
}
if (empty($pktitem)) {
    $pktitem = collect();
}
?>
@section('title', "MOZAIC Practice")
@section('js')
    {{-- <script src="{{ asset('resources/js/paketHelper.js') }}"></script> --}}
    <script>
        function function_elements_add(name, value) {
            $.ajax({
                type: "POST",
                url: "{{ route('item.add-elements') }}",
                data: {
                    'name': name,
                    'value': value,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(msg) {},
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function reset_add() {
            $.ajax({
                type: "GET",
                url: "{{ route('item.add-reset') }}",
                success: function(msg) {
                    location.reload();
                }
            });
        }
        function changeCategory(id, el, from_paket = 0) {
            loading();
            if($('#'+id).val()!=''){
                $('#merchant_id').val($('#'+id).val());
            }
            var merchant_id = $("#" + id).val();
            console.log(id);
            $.ajax({
                type: "POST",
                url: "{{ route('item.category') }}",
                dataType: "html",
                data: {
                    'merchant_id': merchant_id,
                    'from_paket': from_paket,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(return_data) {
                    if (from_paket) {
                        function_elements_add('package_merchant_id', merchant_id);
                        $('#' + el).html(return_data);
                        changeItem($('#' + el).val());
                        return 0;
                    } else {
                        loading(0);
                        setTimeout(function() {
                            loading(0);
                        }, 2000);
                        $('#' + el).html(return_data);
                        function_elements_add('merchant_id', merchant_id);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function changeItem(category) {
            loading();
            var no = $('.pkg-itm').length;
            $.ajax({
                type: "POST",
                url: "{{ route('item.get-item') }}",
                dataType: "html",
                data: {
                    'no': no,
                    'item_category_id': category,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(return_data) {
                    $('#package_item_id').val(1);
                    $('#package_item_id').html(return_data);
                    changeSatuan();
                    function_elements_add('package_item_category', category);
                }
            });
        }
        function checkKemasan() {
            const max = {{ $items['max_kemasan'] ?? 4 }};
            var no = $('.input-kemasan').length;
            while (no > max) {
                removeKemasan('input-kemasan-' + no)
            }
            if (no >= max) {
                $('#add-kmsn').addClass('disabled');
            } else {
                $('#add-kmsn').removeClass('disabled');
            }
        }
        function addKemasan() {
            const max = {{ $items['max_kemasan'] ?? 4 }};
            var no = $('.input-kemasan').length;
            var noa = $('.input-kemasan').length + 1;
            if (no != max) {
                $.ajax({
                    type: "get",
                    url: "{{ route('item.add-kemasan') }}",
                    dataType: "html",
                    success: function(return_data) {
                        location.reload();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
        function removeKemasan(el) {
            $.ajax({
                type: "get",
                url: "{{ route('item.remove-kemasan') }}",
                dataType: "html",
                success: function(return_data) {
                    $('#' + el).remove();
                    checkKemasan()
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function addCategory() {
            location.href = '{{ route('ic.add') }}' + '/' + $('#merchant_id').val();
        }
        function formatRp() {
            var harga = $('#package_price_view').val();
            function_elements_add('package_price_view', harga);
            $('#package_price_view').val(toRp(harga));
            $('#package_price').val(harga);
        }
        function function_change_quantity(item_packge_id, unit_id, value) {
            if (value != '') {
                $("#simpan-brg").prop('disabled', true);
                $.ajax({
                    url: "{{ url('item-package/change-qty') }}" + '/' + item_packge_id + '/' + unit_id + '/' +
                        value,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                    },
                    complete: function() {
                        $("#simpan-brg").prop('disabled', false);
                        setTimeout(function() {
                            $("#simpan-brg").prop('disabled', false);
                        }, 20);
                    }
                });
            }
        }
        function changeCategory(id, el, from_paket = 0) {
            console.log($('#'+id).val());
            if($('#'+id).val()!=''){
                $('#merchant_id').val($('#'+id).val());
            } else if ($('#'+id).val()===null||$('#'+id).val()===undefined ){
                loading(0);
                return 0;
            }
                loading();
            var merchant_id = $("#" + id).val();
            $.ajax({
                type: "POST",
                url: "{{ route('item.category') }}",
                dataType: "html",
                data: {
                    'from_paket': from_paket,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(return_data) {
                    if (from_paket) {
                        function_elements_add('package_merchant_id', merchant_id);
                        $('#' + el).html(return_data);
                        changeItem($('#' + el).val());
                        return 0;
                    } else {
                        loading(0);
                        setTimeout(function() {
                            loading(0);
                        }, 2000);
                        $('#' + el).html(return_data);
                        function_elements_add('merchant_id', merchant_id);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function changeItem(category) {
            loading();
            var id = $("#package_merchant_id").val();
            var no = $('.pkg-itm').length;
            $.ajax({
                type: "POST",
                url: "{{ route('item.get-item') }}",
                dataType: "html",
                data: {
                    'no': no,
                    'merchant_id': id,
                    'item_category_id': category,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(return_data) {
                    $('#package_item_id').val(1);
                    $('#package_item_id').html(return_data);
                    changeSatuan();
                    function_elements_add('package_merchant_id', id);
                    function_elements_add('package_item_category', category);
                }
            });
        }
        function changeSatuan() {
            var package_item_id = $("#package_item_id").val();
            loading();
            $.ajax({
                type: "POST",
                url: "{{ route('item.unit') }}",
                dataType: "html",
                data: {
                    'item_id': package_item_id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(return_data) {
                    $('#package_item_unit').val(1);
                    $('#package_item_unit').html(return_data);
                    function_elements_add('package_item_id', package_item_id);
                },
                complete: function() {
                    loading(0);
                    setTimeout(function() {
                        loading(0);
                    }, 200);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        function save(){
            $("#create_warehouse").val(1);
            $('#confirmModal').modal('hide')
            $('#form-barang').submit();
        }
        $(document).ready(function() {
            // changeCategory('merchant_id', 'item_category_id');
            // changeCategory('package_merchant_id', 'package_item_category', 1);
            checkKemasan();
            if ($('#package_price_view').val() != '') {
                formatRp();
            }
            $("#simpan-brg").click(function (e) {
                e.preventDefault();
                $('#form-barang').submit();
            });
            $("#confirm-save-w-whs").click(function (e) {
                e.preventDefault();
                save();
            });
            if($('#merchant_id_view').val()!=''){
                console.log($('#merchant_id_view').val());
                $('#merchant_id').val($('#merchant_id_view').val());
            }
        });
    </script>
@stop
@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('item.index') }}">Daftar Barang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ubah Barang</li>
        </ol>
    </nav>
@stop
@section('content')
    <h3 class="page-title">
        Form Ubah Barang
    </h3>
    <br />
    @if (session('msg'))
    <div class="alert alert-{{session('type')??'info'}}" role="alert">
        {{ session('msg') }}
    </div>
    @endif
    @if (!empty($msg))
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-exclamation"></i> &nbsp; {{ $msg }}
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
                Form Ubah
            </h5>
            <div class="float-right">
                <button onclick="location.href='{{ route('item.index') }}'" name="Find" class="btn btn-sm btn-info"
                    title="Back"><i class="fa fa-angle-left"></i> Kembali</button>
            </div>
        </div>
        <form method="post" id="form-barang" action="{{ route('item.edit-process') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $counts->count() == 0 ? 'active' : '' }}" href="#barang" role="tab"
                            data-toggle="tab">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $counts->count() == 0 ? '' : '' }}" href="#kemasan" role="tab"
                            data-toggle="tab">Kemasan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{!empty($pkg)&&!$pktitem->count()?"disabled":''}} {{ $counts->count() >= 1 ? 'active' : '' }}" href="#form-pkt" role="tab"
                            data-toggle="tab">Paket</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in {{ $counts->count() == 0 ? 'show active' : '' }}"
                        id="barang">
                        <div class="row form-group mt-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Kategori Barang<a class='red'> *</a></a>
                                    {{ html()->select('item_category_id', $category,$items['item_category_id'] ?? '' )->class(['selection-search-clear', 'select-form', 'form-control'])->attributes(['onchange' => 'function_elements_add(this.name, this.value)', 'form' => 'form-barang','placeholder' => 'Masukkan Kategori', 'data-allow-clear' => 'true', 'autocomplete'=>'off']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Kode Barang<a class='red'> *</a></a>
                                    <input class="form-control input-bb" name="item_code" id="item_code" type="text"
                                        form="form-barang" autocomplete="off"
                                        onchange="function_elements_add(this.name, this.value)"
                                        value="{{ isset($items['item_code']) ? ($items['item_code'] != '' ? $items['item_code'] : $data['item_code']) : $data['item_code'] }}" />
                                    <input class="form-control input-bb" name="item_id" id="item_id" type="text"
                                        form="form-barang" autocomplete="off" value="{{ $data['item_id'] }}" hidden />
                                    <input type="hidden" form="form-barang" name="used_in_package"
                                        value="{{ !empty($pkg) ? 1 : 0 }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Barang<a class='red'> *</a></a>
                                    <input class="form-control input-bb" name="item_name" id="item_name" form="form-barang"
                                        type="text" onchange="function_elements_add(this.name, this.value)"
                                        autocomplete="off"
                                        value="{{ isset($items['item_name']) ? ($items['item_name'] != '' ? $items['item_name'] : $data['item_name']) : $data['item_name'] }}" />
                                </div>
                            </div>
                            <div class="col-md-8 mt-3">
                                <div class="form-group">
                                    <a class="text-dark">Keterangan<a class='red'> *</a></a>
                                    <textarea class="form-control input-bb" name="item_remark" id="item_remark" form="form-barang" type="text"
                                        autocomplete="off" onchange="function_elements_add(this.name, this.value)">{{ isset($items['item_remark']) ? ($items['item_remark'] != '' ? $items['item_remark'] : $data['item_remark']) : $data['item_remark'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="kemasan">
                        <button type="button" onclick="addKemasan('{{ route('item.add-kemasan') }}')" id="add-kmsn"
                            data-toggle="tooltip" data-placement="top" name="Add" class="btn mt-4 btn-sm btn-info"
                            title="Tambah Kategori"><i class="fa fa-plus"></i> Tambah Kemasan</button>
                        <div class="div-kemasan" id="div-kemasan">
                            <input type="hidden" name="base_kemasan" value="{{ $base_kemasan }}" />
                            @for ($x = 1; $x <= $items['kemasan'] + ($base_kemasan - 1); $x++)
                                <div class="input-kemasan" id="input-kemasan-{{ $x }}">
                                    @if ($x != 1)
                                        <a class="float-right text-body" data-toggle="tooltip" data-placement="top"
                                            title="Hapus Kemasan"
                                            onclick="removeKemasan('input-kemasan-{{ $x }}')"><i
                                                class="fa fa-times"></i></a>
                                    @endif
                                    <h5 class="mt-3"><b>Kemasan {{ $x }}</b></h5>
                                    <div class="row form-group mt-2">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="text-dark">Satuan Barang {{ $x }}<a class='red'>
                                                        *</a></a>
                                                {!! Form::select(
                                                    'item_unit_id',
                                                    $itemunits,
                                                    isset($items['item_unit_id' . $x])
                                                        ? $items['item_unit_id' . $x]
                                                        : ($x <= $base_kemasan
                                                            ? $data->packge[$x-1]['item_unit_id' ]
                                                            : ''),
                                                    [
                                                        'class' => 'selection-search-clear required select-form form-control',
                                                        'name' => 'item_unit_id' . $x,
                                                        'id' => 'item_unit_id_' . ($x - 1),
                                                        'onchange' => 'function_elements_add(this.name, this.value)',
                                                        'form' => 'form-barang',
                                                    ],
                                                ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="text-dark">Kuantitas Standar {{ $x }}<a
                                                        class='red'> *</a></a>
                                                <input class="form-control input-bb required" required form="form-barang"
                                                    type="number"
                                                    name="unit[{{$x}}][item_default_quantity]"
                                                    id="item_default_quantity_{{ $x - 1 }}" type="text"
                                                    autocomplete="off"
                                                    onchange="function_elements_add(this.name, this.value)"
                                                    value="{{ isset($items['item_default_quantity' . $x]) ? $items['item_default_quantity' . $x] : ($x <= $base_kemasan ? $data->packge[$x-1]['item_default_quantity']: '') }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="text-dark">Harga Jual {{ $x }}<a class='red'>
                                                        *</a></a>
                                                <input class="form-control input-bb required" required form="form-barang"
                                                    type="number"
                                                    name="item_unit_price{{ $x }}"
                                                    id="item_unit_price_{{ $x - 1 }}" type="text"
                                                    autocomplete="off"
                                                    onchange="function_elements_add(this.name, this.value)"
                                                    value="{{ isset($items['item_unit_price' . $x]) ? $items['item_unit_price' . $x] : ($x <= $base_kemasan ? $data->packge[$x-1]['item_unit_price']: '') }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <a class="text-dark">Harga Beli {{ $x }}<a class='red'>
                                                        *</a></a>
                                                <input class="form-control input-bb required" required form="form-barang"
                                                    type="number"
                                                    name="item_unit_cost{{ $x }}"
                                                    id="item_unit_cost_{{ $x - 1 }}" type="text"
                                                    autocomplete="off"
                                                    onchange="function_elements_add(this.name, this.value)"
                                                    value="{{ isset($items['item_unit_cost' . $x]) ? $items['item_unit_cost' . $x] : ($x <= $base_kemasan ? $data->packge[$x-1]['item_unit_cost']: '') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade {{ $counts->count() >= 1 ? 'show active' : '' }}"
                        id="form-pkt">
                        <div class="row form-group">
                            <div class="col-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Kategori Barang / Paket<a class='red'> *</a></a>
                                    <select class="selection-search-clear required select-form" required
                                        form="form-paket" placeholder="Masukan Kategori" name="package_item_category"
                                        id="package_item_category" onchange="function_elements_add(this.name, this.value)">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <a class="text-dark">Nama Barang<a class='red'> *</a></a>
                                    <select class="selection-search-clear required select-form"
                                        placeholder="Masukan Nama Barang" name="package_item_id" id="package_item_id"
                                        onchange="changeSatuan('{{ route('item.unit') }}','{{ csrf_token() }}')">
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto justify-content-center">
                                <button class="btn btn-sm btn-primary mt-4" type="button"
                                    onclick="addPackageItem('{{ url('item-package/add-item') }}','{{ csrf_token() }}','{{ url('item-package/change-qty') }}')"><i
                                        class="fa fa-plus" id="add-package-item"></i>Tambah Barang</button>
                            </div>
                            <div class="col-md-4 ml-5">
                                <div class="form-group">
                                    <a class="text-dark">Satuan<a class='red'> *</a></a>
                                    <select class="selection-search-clear required select-form"
                                        placeholder="Masukan Kategori Barang" name="package_item_unit"
                                        id="package_item_unit" onchange="function_elements_add(this.name, this.value)">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card border border-dark">
                            <div class="card-header bg-dark clearfix">
                                <h5 class="mb-0 float-left">
                                    Daftar Isi Paket
                                </h5>
                                <div class="form-actions float-right">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" style="width:100%"
                                        class="table table-striped table-bordered table-hover table-full-width">
                                        <thead>
                                            <tr>
                                                <th width="2%" style='text-align:center'>No</th>
                                                <th width="20%" style='text-align:center'>Nama Kategori Barang</th>
                                                <th width="20%" style='text-align:center'>Kode Barang</th>
                                                <th width="20%" style='text-align:center'>Wahana / Merchant</th>
                                                <th width="20%" style='text-align:center'>Nama Barang</th>
                                                <th width="20%" style='text-align:center'>Jumlah</th>
                                                <th width="10%" style='text-align:center'>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="package-table">
                                            @for ($no = 1; $no <= $pktitem->count(); $no++)
                                                @php $row = $paket->where('item_id',array_keys($pktitem[$no-1])[0])->first(); @endphp
                                                <tr class='pkg-itm' id="col-package-item-{{ $row->item_id }}">
                                                    <td style='text-align:center'>{{ $no }}</td>
                                                    <td>{{ $row->category->item_category_name }}</td>
                                                    <td>{{ $row['item_code'] }}</td>
                                                    <td>{{ $row->merchant->merchant_name }}</td>
                                                    <td>{{ $row['item_name'] }}</td>
                                                    <td>
                                                        <div class="row">
                                                            <input
                                                                oninput="function_change_quantity({{ $row->item_id }},{{ $pktitem[$no - 1][$row->item_id][1] }}, this.value)"
                                                                type="number"
                                                                name="item_package_{{ $row->item_id }}_{{ $pktitem[$no - 1][$row->item_id][1] }}_quantity"
                                                                id="item_package_{{ $row->item_id }}_{{ $pktitem[$no - 1][$row->item_id][1] }}_quantity"
                                                                style="width: 100%; text-align: center; height: 30px; font-weight: bold; font-size: 15px"
                                                                class="form-control col input-bb" min='1'
                                                                value="{{ $pktitem[$no - 1][$row->item_id][0] }}"
                                                                autocomplete="off">
                                                            <div class="col-auto">
                                                                {{ $unit->where('item_unit_id', $pktitem[$no - 1][$row->item_id][1])->pluck('item_unit_name')[0] }}
                                                                </col>
                                                            </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteIsiPaket('{{ $row->item_id }}','{{ $pktitem[$no - 1][$row->item_id][1] }}','{{ url('package/delete-item/') }}')">Hapus</button>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div class="form-actions float-right">
                    <button type="reset" name="Reset" class="btn btn-danger" onclick="reset_add();"><i
                            class="fa fa-times"></i> Batal</button>
                    <button type="button" id="simpan-brg" class="btn btn-primary" ><i
                            class="fa fa-check"></i>
                        Simpan</button>
                </div>
            </div>
    </div>
    </form>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="confirmModalLabel">Perhatian !</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p class="d-inline">Wahana "<b class="d-inline" id="mname">Merchant</b>" tidak memiliki gudang. Apakah anda ingin sistem mebuat gudang otomatis?</p> <small>(Gudang akan diberi nama "<b class="d-inline">Gudang <div class="d-inline" id="wname">Merchant</div></b>")</small>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
              <a type="button" href="{{url('warehouse/add')}}" class="btn btn-info">Buat Gudang Manual</a>
              <button type="button" class="btn btn-primary" id="confirm-save-w-whs">Ya</button>
            </div>
          </div>
        </div>
      </div>
@stop
@section('footer')
@stop
@section('css')
@stop
