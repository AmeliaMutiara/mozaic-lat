<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreferenceVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\PreferenceVoucherDataTable;

class PreferenceVoucherController extends Controller
{
    public function index(PreferenceVoucherDataTable $table)
    {
        Session::forget('datases');
        $data = PreferenceVoucher::where('company_id', Auth::user()->company_id)
        ->get();
        return  $table->render('content.PreferenceVoucher.List.index', compact('data'));
    }

    public function addPreferenceVoucher()
    {
        $datases = Session::get('datases');
        return view('content.PreferenceVoucher.Add.index', compact('datases'));
    }

    public function addElementsPreferenceVoucher(Request $request)
    {
        $datases = Session::get('datases');
        if(!$datases || $datases == '') {
            $datases['voucher_code']        = '';
            $datases['voucher_amount']      = '';
            $datases['start_voucher']       = '';
            $datases['end_voucher']         = '';
        }
        $datases[$request->name] = $request->value;
        $datases = Session::put('datases', $datases);
    }

    public function resetElementsPreferenceVoucher(Request $request)
    {
        Session::forget('datases');
        return redirect()->back();
    }

    public function addProcessPreferenceVoucher(Request $request)
    {
        $request->validate([
            'voucher_code'          => 'required',
            'voucher_amount'        => 'required',
            'start_voucher'         => 'required',
            'end_voucher'           => 'required',
        ],[
            'voucher_code.required'         => 'Kode Voucher harap diisi',
            'voucher_amount.required'       => 'Nominal Voucher harap diisi',
        ]);

        try {
            DB::beginTransaction();
            $data = PreferenceVoucher::create([
                'voucher_code'      => $request->voucher_code,
                'voucher_amount'    => $request->voucher_amount,
                'start_voucher'     => $request->start_voucher,
                'end_voucher'       => $request->end_voucher,
                'company_id'        => Auth::user()->company_id
            ]);
            DB::commit();
            return redirect()->route('pv.index')->with(['msg' => 'Berhasil Menambahkan Voucher', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('pv.add')->with(['msg' => 'Gagal Menambahkan Voucher', 'type' => 'danger']);
        }
    }

    public function editPreferenceVoucher($voucher_id)
    {
        $data = PreferenceVoucher::where('voucher_id', $voucher_id)
        ->first();
        return view('content.PreferenceVoucher.Edit.index', compact('data'));
    }

    public function editProcessPreferenceVoucher(Request $request)
    {
        $request->validate([
            'voucher_code'          => 'required',
            'voucher_amount'        => 'required',
            'start_voucher'         => 'required',
            'end_voucher'           => 'required',
        ],[
            'voucher_code.required'         => 'Kode Voucher harap diisi',
            'voucher_amount.required'       => 'Nominal Voucher harap diisi',
        ]);

        try {
            DB::beginTransaction();
            $table                      = PreferenceVoucher::findOrFail($request->voucher_id);
            $table->voucher_code        = $request->voucher_code;
            $table->voucher_amount      = $request->voucher_amount;
            $table->start_voucher       = $request->start_voucher;
            $table->end_voucher         = $request->end_voucher;
            $table->updated_id          = Auth::id();
            $table->save();
            DB::commit();
            return redirect()->route('pv.index')->with(['msg' => 'Berhasil Mengubah Voucher', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('pv.edit')->with(['msg' => 'Gagal Mengubah Voucher', 'type' => 'danger']);
        }
    }

    public function deletePreferenceVoucher($voucher_id)
    {
        try {
            DB::beginTransaction();
            PreferenceVoucher::find($voucher_id)->delete();
            DB::commit();
            return redirect()->route('pv.index')->with(['msg' => 'Berhasil Menghapus Voucher', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('pv.index')->with(['msg' => 'Gagal Menghapus Voucher', 'type' => 'danger']);
        }
    }
}