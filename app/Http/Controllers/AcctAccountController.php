<?php

namespace App\Http\Controllers;

use App\Models\AcctAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\AcctAccountDataTable;

class AcctAccountController extends Controller
{
    public function index(AcctAccountDataTable $table)
    {
        Session::forget('datases');
        $data = AcctAccount::select('account_code', 'account_name', 'account_group', 'account_type_id', 'account_status', 'account_id')
        ->where('company_id', Auth::user()->company_id)
        ->get();
        return $table->render('content.AcctAccount.List.index');
    }

    public function addAcctAccount()
    {
        $datases = Session::get('datases');
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        $account_type = array(
            '0' => 'NA - Neraca Aktif',
            '1' => 'NP - Neraca Pasif',
            '2' => 'RA - Rugi Laba (A)',
            '3' => 'RP - Rugi Laba (b)',
        );
        return view('content.AcctAccount.Add.index', compact('datases', 'status', 'account_type'));
    }

    public function addElementsAcctAccount(Request $request)
    {
        $datases = Session::get('datases');
        if(!$datases || $datases == '') {
            $datases['account_code']        = '';
            $datases['account_name']        = '';
            $datases['account_group']       = '';
            $datases['account_status']      = '';
            $datases['account_type_id']     = '';
        }
        $datases[$request->name] = $request->value;
        Session::put('datases', $datases);
    }

    public function processAddAcctAccount(Request $request)
    {
        $fields = $request->validate([
            'account_code'      => 'required',
            'account_name'      => 'required',
            'account_group'     => 'required',
            'account_status'    => 'required',
            'account_type_id'   => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = AcctAccount::create([
                'account_code'          => $fields['account_code'],
                'account_name'          => $fields['account_name'],
                'account_group'         => $fields['account_group'],
                'account_default_status'=> $fields['account_status'],
                'account_status'        => $fields['account_status'],
                'account_type_id'       => $fields['account_type_id'],
                'company_id'            => Auth::user()->company_id
            ]);
            DB::commit();
            return redirect()->route('account.index')->with(['msg' => 'Berhasil Menambahkan Data Perkiraan', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('account.add')->with(['msg' => 'Gagal Menambahkan Data Perkiraan', 'type' => 'danger']);
        }
    }

    public function addResetAcctAccount()
    {
        Session::forget('datases');
        return redirect()->back();
    }

    public function getStatus($account_status)
    {
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        return $status[$account_status];
    }

    public function getType($account_type_id)
    {
        $account_type = array(
            '0' => 'NA - Neraca Aktif',
            '1' => 'NP - Neraca Pasif',
            '2' => 'RA - Rugi Laba (A)',
            '3' => 'RP - Rugi Laba (b)',
        );
        return $account_type[$account_type_id];
    }

    public function editAcctAccount($account_id)
    {
        $data = AcctAccount::select('account_code', 'account_id', 'account_name', 'account_group', 'account_status', 'account_type_id')
        ->where('company_id', Auth::user()->company_id)
        ->where('account_id', $account_id)
        ->first();
        $status = array(
            '0' => 'Debit',
            '1' => 'Kredit'
        );
        $account_type = array(
            '0' => 'NA - Neraca Aktif',
            '1' => 'NP - Neraca Pasif',
            '2' => 'RA - Rugi Laba (A)',
            '3' => 'RP - Rugi Laba (b)',
        );
        return view('content.AcctAccount.Edit.index', compact('data', 'status', 'account_type'));
    }

    public function processEditAcctAccount(Request $request)
    {
        $fields = $request->validate([
            'account_id'        => '',
            'account_code'      => 'required',
            'account_name'      => 'required',
            'account_group'     => 'required',
            'account_status'    => 'required',
            'account_type_id'   => 'required'
        ]);
        try {
            DB::beginTransaction();
            $table                              = AcctAccount::findOrFail($fields['account_id']);
            $table->account_code                = $fields['account_code'];
            $table->account_name                = $fields['account_name'];
            $table->account_group               = $fields['account_group'];
            $table->account_default_status      = $fields['account_status'];
            $table->account_status              = $fields['account_status'];
            $table->account_type_id             = $fields['account_type_id'];
            $table->save();
            DB::commit();
            return redirect()->route('account.index')->with(['msg' => 'Berhasil Mengubah Data Perkiraan', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('account.edit')->with(['msg' => 'Gagal Mengubah Data Perkiraan', 'type' => 'danger']);
        }
    }

    public function deleteAcctAccount($account_id)
    {
        try {
            DB::beginTransaction();
            AcctAccount::find($account_id)->delete();
            DB::commit();
            return redirect()->route('account.index')->with(['msg' => 'Berhasil Menghapus Data Perkiraan', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('account.index')->with(['msg' => 'Gagal Menghapus Data Perkiraan', 'type' => 'danger']);
        }
    }
}