@inject('AccountSetting', 'App\Http\Controllers\AcctAccountSettingController')

@extends('adminlte::page')

@section('title', 'MOZAIC Minimarket')

@section('content_header')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Setting Akun</li>
    </ol>
</nav>

@stop

@section('content')

<h3 class="page-title">
    Form Daftar Setting Akun
</h3>
<br/>
@if(session('msg'))
<div class="alert alert-info" role="alert">
    {{session('msg')}}
</div>
@endif

@if(count($errors) > 0)
<div class="alert alert-danger" role="alert">
    @@foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
    @endforeach
</div>
@endif
<div class="card border border-dark">
    <div class="card-header border-dark bg-dark">
        <h5 class="mb-0 float-left">
            Form Setting Akun
        </h5>
    </div>
</div>

<form method="post" action="{{route('AS.add-process')}}" enctype="multipart/form-data">
@csrf
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#pembelian" role="tab" data-toggle="tab">Pembelian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#penjualan" role="tab" data-toggle="tab">Penjualan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#pengeluaran" role="tab" data-toggle="tab">Pengeluaran</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade show acctive" id="pembelian">
                <table class="table table-borderless mt-3">
                    <tr>
                        <th colspan="3" style="text-align: center !important ;width: 100% !important">Pembelian Tunai</th>
                    </tr>
                    <tr>
                        <th style="text-align: left !important; width: 40% !important">Kas</th>
                        <td style="text-align: left !important; width: 30% !important">
                            {!! Form::select(0, $accountlist, $AccountSetting->getAccountId('purchase_cash_account'),['class' => 'form-control selection-search-clear select-form','name'=>'purchase_cash_account_id','id'=>'purchase_cash_account_id']) !!}
                        </td>
                        <td style="text-align: left !important; width: 30% !important">
                            {!! Form::select(0, $accountlist, $AccountSetting->getAccountSettingStatus('purchase_cash_account'),['class' => 'form-control selection-search-clear select-form','name'=>'purchase_cash_account_status','id'=>'purchase_cash_account_status']) !!}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left !important; width: 40% !important">Pembelian</th>
                        <td style="text-align: left !important; width: 30%">
                            {!! Form::select(0, $accountlist, $AccountSetting->getAccountId('purchase_account'),['class' => 'form-control selection-search-clear select-form','name'=>'purchase_account_id','id'=>'purchase_account_id'])!!}
                        </td>
                        <td style="text-align: left !important; width: 30%">
                            {!! Form::select(0, $status, $AccountSetting->getAccountSettingStatus('purchase_account'),['class' => 'form-control selection-search-clear select-form','name'=>'purchase_account_status','id'=>'purchase_account_status'])!!}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: center !important ;width: 100% !important">Pembelian Hutang</th>
                    </tr>
                    <tr>
                        <th style="text-align: left !important; width: 40% !important">Pembelian</th>
                        <td style="text-align: left !important; width: 30% !important">
                            {!! Form::select(0, $accountlist, $AccountSetting->getAccountId('purchase_payable_account'),['class' => 'selection-search-clear select-form', 'name'=>'purchase_payable_account_id','id'=>'purchase_payable_account_id']) !!}
                        </td>
                        <td style="text-align: left !important; width: 30% !important">
                            {!! Form::select(0, $status, $AccountSetting->getAccountSettingStatus('purchase_payable_account'),['class' => 'selection-search-clear select-form', 'name'=>'purchase_payable_account_status','id'=>'purchase_payable_account_status']) !!}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left !important; width: 40% !important">Hutang</th>
                        <td>
                            {!! Form::select(0, $accountlist, $AccountSetting->getAccountId('purchase_cash_payable_account'),['class' => 'selection-search-clear select-form','name' =>'purchase_cash_payable_account_id','id'=>purchase_cash_payable_account_id])!!}
                        </td>
                        <td>
                            {!! Form::select(0, $status, $AccountSetting->getAccountSettingStatus('purchase_cash_payable_account'),['class' => 'selection-search-clear select-form','name' =>'purchase_cash_payable_account_id','id'=>purchase_cash_payable_account_id])!!}
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</form>