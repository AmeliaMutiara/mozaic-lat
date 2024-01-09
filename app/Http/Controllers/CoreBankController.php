<?php

namespace App\Http\Controllers;

use App\Models\CoreBank;
use App\Models\AcctAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DataTables\CoreBankDataTable;
use Illuminate\Support\Facades\Session;

class CoreBankController extends Controller
{
    public function index(CoreBankDataTable $table)
    {
        Session::forget('databank');
        $data = CoreBank::select('bank_name', 'account_id', 'bank_id')
        ->where('company_id', Auth::user()->company_id)
        ->get();
        return $table->render('content.CoreBank.List.index', compact('data'));
    }

    public function addElementsCoreBank(Request $request)
    {
        $databank = Session::get('databank');
        if(!$databank || $databank == '') {
            $databank['bank_name']      = '';
            $databank['account_id']     = '';
        }
        $databank[$request->name] = $request->value;
        Session::put('databank', $databank);
    }

    public function resetElementsCoreBank(Request $request)
    {
        Session::forget('databank');
        return redirect()->back();
    }

    public function addCoreBank()
    {
        $databank = Session::get('databank');
        $accountlist = AcctAccount::select(DB::raw("CONCAT(account_code,' - ',account_name) AS full_account"), 'account_id')
        ->where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('full_account', 'account_id');
        return view('content.CoreBank.Add.index', compact('databank', 'accountlist'));
    }

    public function processAddCoreBank(Request $request)
    {
        $request->validate([
            'bank_name'     => 'required',
            'account_id'    => 'required',
            'bank_code'     => 'required',
            'account_no'    => 'required',
        ],[
            'bank_name.required'    => 'Nama Bank Harus Dimasukkan',
            'account_id.required'   => 'Harus Memilih Salah Satu Akun Perkiraan',
            'bank_code.required' => 'Harus Memasukkan Kode Akun',
            'account_no.required' => 'Harus Memasukkan Nomor Rekening',
        ]);
        try {
            DB::beginTransaction();
            $data = CoreBank::create([
                'bank_name'     => $request->bank_name,
                'account_id'    => $request->account_id,
                'bank_code'     => $request->bank_code,
                'account_no'    => $request->account_no,
                'onbehalf'      => $request->onbehalf,
                'bank_remark'   => $request->bank_remark,
                'company_id'    => Auth::user()->company_id,
                'created_id'    => Auth::id(),
                'updated_id'    => Auth::id()
            ]);
            DB::commit();
            return redirect()->route('bank.index')->with(['msg' => 'Berhasil Menambahkan Data Bank', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('bank.add')->with(['msg' => 'Gagal Menambahkan Data Bank', 'type' => 'danger']);
        }
    }

    public function editCoreBank($bank_id)
    {
        $data = CoreBank::where('bank_id', $bank_id)
        ->first();

        $accountlist = AcctAccount::select(DB::raw("CONCAT(account_code,' - ',account_name) AS full_account"), 'account_id')
        ->where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('full_account', 'account_id');
        return view('content.CoreBank.Edit.index', compact('data', 'accountlist'));
    }

    public function processEditCoreBank(Request $request)
    {
        $fields = $request->validate([
            'bank_id'       => '',
            'bank_name'     => 'required',
            'account_id'    => 'required',
            'bank_code'     => 'required',
            'account_no'    => 'required',
        ]);
        try {
            DB::beginTransaction();
            $table              = CoreBank::findOrFail($fields['bank_id']);
            $table->bank_name   = $fields['bank_name'];
            $table->account_id  = $fields['account_id'];
            $table->bank_code   = $fields['bank_code'];
            $table->account_no  = $fields['account_no'];
            $table->onbehalf    = $request->onbehalf;
            $table->bank_remark = $request->bank_remark;
            $table->updated_id  = Auth::id();
            $table->save();
            DB::commit();
            return redirect()->route('bank.index')->with(['msg' => 'Berhasil Mengubah Data Bank', 'type' => 'success']);
        } catch (\Exception $e) {
        DB::rollBack();
        dd($e);
        report($e);
        return redirect()->route('bank.edit')->with(['msg' => 'Gagal Mengubah Data Bank', 'type' => 'danger']);
        }
    }

    public function deleteCoreBank($bank_id)
    {
        try {
            DB::beginTransaction();
            CoreBank::find($bank_id)->delete();
            DB::commit();
            return redirect()->route('bank.index')->with(['msg' => 'Berhasil Menghapus Data Bank', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('bank.index')->with(['msg' => 'Gagal Menghapus Data Bank', 'type' => 'danger']);
        }
    }

    public function getAccountName($account_id)
    {
        $data = AcctAccount::select('account_code', 'account_name')
        ->where('account_id', $account_id)
        ->first();

        return $data['account_code']. ' - ' .$data['account_name'];
    }
}
