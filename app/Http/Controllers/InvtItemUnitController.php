<?php

namespace App\Http\Controllers;

use App\Models\InvtItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\InvtItemUnitDataTable;

class InvtItemUnitController extends Controller
{
    public function index(InvtItemUnitDataTable $table)
    {
        Session::forget('itemunits');
        $data = InvtItemUnit::select('item_unit_code', 'item_unit_name', 'item_unit_id')
        ->where('company_id', Auth::user()->company_id)
        ->get();
        return $table->render('content.InvtItemUnit.List.index', compact('data'));
    }

    public function addInvtItemUnit()
    {
        $itemunits = Session::get('itemunits');
        return view('content.InvtItemUnit.Add.index', compact('itemunits'));
    }

    public function addElementsInvtItemUnit(Request $request)
    {
        $itemunits = Session::get('itemunits');
        if(!$itemunits || $itemunits == '') {
            $itemunits['item_unit_code']    = '';
            $itemunits['item_unit_name']    = '';
            $itemunits['item_unit_remark']  = '';
        }
        $itemunits[$request->name] = $request->value;
        Session::put('itemunits', $itemunits);
    }

    public function addReset()
    {
        Session::forget('itemunits');
        return redirect()->back();
    }

    public function processAddInvtItemUnit(Request $request)
    {
        $fields = $request->validate([
            'item_unit_code'        => 'required',
            'item_unit_name'        => 'required',
            'item_unit_remark'      => ''
        ],[
            'item_unit_code.required'   => 'Kode Unit Harus Diisi',
            'item_unit_name.required'   => 'Nama Unit Harus Diisi'
        ]);

        try {
            DB::beginTransaction();
            $data = InvtItemUnit::create([
                'item_unit_code'        => $fields['item_unit_code'],
                'item_unit_name'        => $fields['item_unit_name'],
                'item_unit_remark'      => $fields['item_unit_remark'],
                'company_id'            => Auth::user()->company_id
            ]);
            DB::commit();
            return redirect()->route('itemunit.index')->with(['msg' => 'Berhasil Menambahkan Unit Item', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('itemunit.add')->with(['msg' => 'Gagal Menambahkan Unit Item', 'type' => 'danger']);
        }
    }

    public function editInvtItemUnit($item_unit_id)
    {
        $itemunits = InvtItemUnit::select('item_unit_code', 'item_unit_id', 'item_unit_name', 'item_unit_remark')
        ->where('item_unit_id', $item_unit_id)
        ->first();
        return view('content.InvtItemUnit.Edit.index', compact('itemunits'));
    }

    public function processEditInvtItemUnit(Request $request)
    {
        $fields = $request->validate([
            'item_unit_id'          => '',
            'item_unit_code'        => 'required',
            'item_unit_name'        => 'required',
            'item_unit_remark'      => ''
        ],[
            'item_unit_code.required'   => 'Kode Unit Harus Diisi',
            'item_unit_name.required'   => 'Nama Unit Harus Diisi'
        ]);

        try {
            DB::beginTransaction();
            $table                      = InvtItemUnit::findOrFail($fields['item_unit_id']);
            $table->item_unit_code      = $fields['item_unit_code'];
            $table->item_unit_name      = $fields['item_unit_name'];
            $table->item_unit_remark    = $fields['item_unit_remark'];
            $table->updated_id          = Auth::id();
            $table->save();
            DB::commit();
            return redirect()->route('itemunit.index')->with(['msg' => 'Berhasil Mengubah Unit Item', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('itemunit.edit')->with(['msg' => 'Gagal Mengubah Unit Item', 'type' => 'danger']);
        }
    }

    public function deleteInvtItemUnit($item_unit_id)
    {
        try {
            DB::beginTransaction();
            InvtItemUnit::find($item_unit_id)->delete();
            DB::commit();
            return redirect()->route('itemunit.index')->with(['msg' => 'Berhasil Menghapus Unit Item', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('itemunit.index')->with(['msg' => 'Gagal Menghapus Unit Item', 'type' => 'danger']);
        }
    }
}
