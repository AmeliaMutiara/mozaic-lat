<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\InvtWarehouse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\InvtWarehouseDataTable;
use App\Models\SalesMerchant;

class InvtWarehouseController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(InvtWarehouseDataTable $table)
    {
        Session::forget('warehouses');
        $data = InvtWarehouse::select('warehouse_id', 'warehouse_code', 'warehouse_name')
        ->where('company_id', Auth::user()->company_id);
        return $table->render('content.InvtWarehouse.List.index');
    }
    public function addWarehouse()
    {
        $warehouses = Session::get('warehouses');
        return view('content.InvtWarehouse.Add.index', compact('warehouses'));
    }
    public function addElementsWarehouse(Request $request)
    {
        $warehouses  = Session::get('warehouses');
        if(!$warehouses || $warehouses == ''){
            $warehouses['warehouse_code']       = '';
            $warehouses['warehouse_name']       = '';
            $warehouses['warehouse_phone']      = '';
            $warehouses['warehouse_address']    = '';
        }
        $warehouses[$request->name] = $request->value;
        Session::put('warehouses', $warehouses);
    }
    public function processAddWarehouse(Request $request)
    {
        $fields = $request->validate([
            'warehouse_code'    => 'required',
            'warehouse_name'    => 'required',
            'warehouse_phone'   => 'required',
            'warehouse_address' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $data = InvtWarehouse::create([
                'warehouse_code'    => $fields['warehouse_code'],
                'warehouse_name'    => $fields['warehouse_name'],
                'warehouse_phone'   => $fields['warehouse_phone'],
                'warehouse_address' => $fields['warehouse_address'],
                'company_id'        => Auth::user()->company_id,
                'created_id'        => Auth::id(),
                'updated_id'        => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route('warehouse.index')->with(['msg'=> 'Berhasil Menambahkan Data Gudang', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('warehouse.add')->with(['msg'=> 'Gagl Menambahkan Data Gudang', 'type' => 'danger']);
        }
    }
    public function editWarehouse($warehouse_id)
    {
        $data   = InvtWarehouse::where('warehouse_id',$warehouse_id)->first();
        return view('content.InvtWarehouse.Edit.index', compact('data'));
    }
    public function processEditWarehouse(Request $request)
    {
        $fields = $request->validate([
            'warehouse_id'      => '',
            'warehouse_code'    => 'required',
            'warehouse_name'    => 'required',
            'warehouse_phone'   => 'required',
            'warehouse_address' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $table                      = InvtWarehouse::findOrFail($fields['warehouse_id']);
            $table->warehouse_code      = $fields['warehouse_code'];
            $table->warehouse_name      = $fields['warehouse_name'];
            $table->warehouse_phone     = $fields['warehouse_phone'];
            $table->warehouse_address   = $fields['warehouse_address'];
            $table->updated_id          = Auth::id();
            $table->save();
            DB::commit();
            return redirect()->route('warehouse.index')->with(['msg' => 'Berhasil Mengubah Data Gudang', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('warehouse.index', $fields['warehouse_id'])->with(['msg' => 'Berhasil Mengubah Data Gudang', 'type' => 'Danger']);
        }
    }
    public function deleteWarehouse($warehouse_id)
    {
        try {
            DB::beginTransaction();
            InvtWarehouse::find($warehouse_id)->delete();
            DB::commit();
            return redirect()->route('warehouse.index')->with(['msg'=> 'Berhasil Menghapus Data Gudang', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('warehouse.index')->with(['msg'=> 'Gagal Mengubah Data Gudang', 'type' => 'danger']);
        }
    }
    public function addResetWarehouse()
    {
        Session::forget('warehouses');
        return redirect('/warehouse/add-warehouse');
    }

    public function checkWarehouse(Request $request)
    {
        $datawarehouse = InvtWarehouse::select('*')
        ->where('merchant_id', $request->merchant_id)
        ->first();

        if ($datawarehouse == null) {
            $return_data = '';
            return $return_data;
        } else {
            $return_data = 1;
            return $return_data;
        }
    }

    public function checkWarehouseDtl(Request $request)
    {
        $datawarehouse = InvtWarehouse::where('merchant_id', $request->merchant_id)
        ->first();
        if ($datawarehouse == null) {
            $datamerchant = SalesMerchant::find($request->merchant_id);
            return response(['count' => 0, 'merchant' => $datamerchant->merchant_name]);
        } else {
            return response(['count' => 1]);
        }
    }
}
