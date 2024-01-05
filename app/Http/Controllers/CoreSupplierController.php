<?php

namespace App\Http\Controllers;

use App\Models\CoreSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\CoreSupplierDataTable;

class CoreSupplierController extends Controller
{
    public function index(CoreSupplierDataTable $table)
    {
       Session::forget('datasupplier');
       $data = CoreSupplier::select('supplier_name','supplier_phone','supplier_address','supplier_id')
       ->where('company_id', Auth::user()->company_id)
       ->get();
       return $table->render('content.CoreSupplier.List.index');
    }
    public function addElementsCoreSupplier(Request $request)
    {
        $datasupplier = Session::get('datasupplier');
        if(!$datasupplier || $datasupplier == ''){
            $datasupplier['supplier_name']      = '';
            $datasupplier['supplier_phone']     = '';
            $datasupplier['supplier_address']   = '';
        }
        $datasupplier[$request->name] = $request->value;
        Session::put('datasupplier', $datasupplier);
    }

    public function resetElementsCoreSupplier()
    {
        Session::forget('datasupplier');

        return redirect()->back();
    }

    public function addCoreSupplier()
    {
        $suppliers = Session::get('datasupplier');

        return view('content.CoreSupplier.Add.index', compact('suppliers'));
    }

    public function processAddCoreSupplier(Request $request)
    {
        $request->validate(['supplier_name' => 'required']);
        try {
            DB::beginTransaction();
            $data = CoreSupplier::create([
                'supplier_name'     => $request->supplier_name,
                'supplier_phone'    => $request->supplier_phone,
                'supplier_address'  => $request->supplier_address,
                'company_id'        => Auth::user()->company_id,
                'created_id'        => Auth::id(),
                'updated_id'        => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route('supplier.index')->with(['msg' => 'Berhasil Menambahkan Data Supplier', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('supplier.add')->with(['msg' => 'Gagal Menambahkan Data Supplier', 'type' => 'danger']);
        }
    }

    public function editCoreSupplier($supplier_id)
    {
        $data = CoreSupplier::select('supplier_name','supplier_phone','supplier_address','supplier_id')
        ->where('supplier_id', $supplier_id)
        ->first();

        return view('content.CoreSupplier.Edit.index', compact('data'));
    }

    public function processEditCoreSupplier(Request $request)
    {
        try {
            DB::beginTransaction();
            $table                      = CoreSupplier::findOrFail($request->supplier_id);
            $table->supplier_name       = $request->supplier_name;
            $table->supplier_phone      = $request->supplier_phone;
            $table->supplier_address    = $request->supplier_address;
            $table->updated_id          = Auth::id();
            $table->save();
            DB::commit();
            return redirect()->route('supplier.index')->with(['msg' => 'Berhasil Mengubah Data Supplier', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('supplier.edit')->with(['msg' => 'Gagal Menambahkan Data Supplier', 'type' => 'danger']);
        }
    }

    public function deleteCoreSupplier($supplier_id)
    {
        try {
            DB::beginTransaction();
            CoreSupplier::find($supplier_id)->delete();
            DB::commit();
            return redirect()->route('supplier.index')->with(['msg' => 'Berhasil Menghapus Data Supplier', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('supplier.index')->with(['msg' => 'Gagal Mengubah Data Supplier', 'type' => 'danger']);
        }
    }
}