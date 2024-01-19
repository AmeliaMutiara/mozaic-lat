<?php

namespace App\Http\Controllers;

use App\Models\InvtItem;
use App\Models\SystemMenu;
use App\Helpers\ItemHelper;
use App\Models\InvtItemUnit;
use Illuminate\Http\Request;
use App\Models\InvtItemStock;
use App\Models\InvtWarehouse;
use function Termwind\render;
use App\Models\InvtItemPackge;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\InvtItemCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DataTables\InvtItemDataTable;
use Illuminate\Support\Facades\Session;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class InvtItemController extends Controller
{
    public function index(InvtItemDataTable $table)
    {
        Session::forget('items');
        Session::forget('paket');
        $data = InvtItem::with('merchant', 'category')
        ->where('item_status', 0)
        ->where('company_id', Auth::user()->company_id);
        if(Auth::id()!=1||Auth::user()->merchant_id!=null){
            $data->where('merchant_id', Auth::user()->merchant_id);
        }
        $data = $data->get();
        return $table->render('content.InvtItem.List.index', compact('data'));
    }

    public function addItem()
    {
        Session::put('token', Str::uuid());
        $canAddCategory = 0;
        $counts = collect();
        $items = Session::get('items');
        $pktitem = collect(Session::get('paket'));
        $unit = InvtItemUnit::get(['item_unit_id', 'item_unit_name']);
        foreach ($pktitem as $key => $val) {
        if(!$counts->contains(collect($val)->keys()[0])) {
            $counts->push(collect($val)->keys()[0]);
        }
        }

        $paket = InvtItem::with('category','merchant')->wherein('item_id', $counts)->where('item_status', 0)->get();
        $itemunits = InvtItemUnit::where('company_id', Auth::user()->company_id)
            ->get()
        ->pluck('item_unit_name', 'item_unit_id');
        $category = InvtItemCategory::where('company_id', Auth::user()->company_id)
            ->get()
            ->pluck('item_category_name', 'item_category_id');
        $merchant = SalesMerchant::where('company_id', Auth::user()->company_id)->get()->pluck('merchant_name', 'merchant_id');
        $allmerchant = SalesMerchant::get()->pluck('merchant_name', 'merchant_id');
        $invtitm = InvtItem::get()->pluck('item_name', 'item_id');
        $canAddCategory=!empty(User::with('group.maping.menu')
                                ->find(Auth::id())->group->maping->where('id_menu', SystemMenu::where('id', 'item-category')->first()->id_menu));

        return view('content.InvtItem.Add.index', compact('category', 'pktitem', 'allmerchant', 'itemunits', 'items', 'merchant', 'invtitm', 'canAddCategory', 'paket', 'counts', 'unit'));
    }

    public function addItemElements(Request $request)
    {
        $items = Session::get('items');
        if(!$items || $items == '') {
            $items['item_code']             = '';
            $items['item_name']             = '';
            $items['item_barcode']          = '';
            $items['item_remark']           = '';
            $items['item_quantity']         = '';
            $items['item_price']            = '';
            $items['package_item_id']       = 1;
            $items['item_cost']             = '';
            $items['item_category_id']      = '';
            $items['kemasan']               = 1;
            $items['merchant_id']           = '';
            $items['max_kemasan']           = 4;
        }
        $items[$request->name] = $request->value;
        Session::put('items', $items);
    }

    public function addResetItem()
    {
        Session::forget('items');
        return redirect()->back();
    }

    public function processAddItem(Request $request)
    {
        if(empty(Session::get('token'))){
            return redirect()->route('item.index')->with('msg', 'Tambah Barang Berhasil');
        }

        $fields = $request->validate([
            'item_category_id'    => 'required|integer',
            'item_code'           => 'required',
            'item_name'           => 'required',
            'item_unit_id1'       => 'required',
        ],[
            'item_category_id.integer'  => 'Wahana / Merchant Tidak Memiliki Kategori',
            'item_unit_id1.required'    => 'Harap Masukkan Satuan 1 (Jika satuan 1 susah dimasukkan tapi masih muncul error ini, maka coba refresh halaman web)'
        ]);

        $warehouse = InvtWarehouse::where('company_id', Auth::user()->company_id)
        ->where('merchant_id', $request->merchant_id)
        ->orWhereNull('merchant_id')
        ->get();

        try {
            DB::beginTransaction();
            $merchant = SalesMerchant::find($request->merchant_id);
            $warehousecode = preg_replace('/[^A-Z]/', '', $merchant->merchant_name);
            if(!$warehouse->count()) {
                if($request->create_werehouse == 1) {
                    InvtWarehouse::create([
                        'merchant_id'           => $request->merchant_id,
                        'warehouse_code'        => "GD{$warehouse}",
                        'warehouse_name'        => "Gudang {$merchant->merchant_name}",
                        'created_id'            => Auth::id(),
                        'company_id'            => Auth::user()->company_id
                    ]);
                } else {
                    return redirect()->route('item.add')->with('msg', 'Merchant Tidak Memiliki WareHouse, Harap Tambah Warehouse');
                }
            }
            $data = InvtItem::create([
                'item_category_id'         => $fields['item_category_id'],
                'item_code'                => $fields['item_code'],
                'item_name'                => $fields['item_name'],
                'merchant_id'              => $request->merchant_id,
                'item_remark'              => $request->item_remark,
                // *Kemasan
                'item_unit_id1'            => $request->item_unit_id1,
                'item_default_quantity1'   => $request->item_default_quantity,
                'item_unit_price1'         => $request->item_unit_price1,
                'item_unit_cost1'          => $request->item_unit_cost1,
                'item_unit_id2'            => $request->item_unit_id2,
                'item_default_quantity2'   => $request->item_default_quantity2,
                'item_unit_price2'         => $request->item_unit_price2,
                'item_unit_cost2'          => $request->item_unit_cost2,
                'item_unit_id3'            => $request->item_unit_id3,
                'item_default_quantity3'   => $request->item_default_quantity3,
                'item_unit_price3'         => $request->item_unit_price3,
                'item_unit_cost3'          => $request->item_unit_cost3,
                'item_unit_id4'            => $request->item_unit_id4,
                'item_default_quantity4'   => $request->item_default_quantity4,
                'item_unit_price4'         => $request->item_unit_price4,
                'item_unit_cost4'          => $request->item_unit_cost4,
                'company_id'               => Auth::user()->company_id
            ]);

            $item = InvtItem::orderBy('created_at', 'DESC')->where('company_id', Auth::user()->company_id)->first();
            foreach ($warehouse as $key => $val) {
                InvtItemStock::create([
                    'company_id'            => $item['company_id'],
                    'warehouse_id'          => $val['warehouse_id'],
                    'item_id'               => $item['item_id'],
                    'item_unit_id'          => $request['item_unit_id_1'],
                    'item_category_id'      => $item['item_category_id'],
                    'last_balance'          => 0
                ]);
            }

            $itm = "Barang";
            if(!empty(Session::get('paket'))){
                $itm = "Paket";
                $paket = collect(Session::get('paket'));
                foreach($paket as $val) {
                    InvtItemPackge::create([
                        'item_id'           => $item['item_id'],
                        'package_item_id'   => array_keys($val)[0],
                        'item_quantity'     => $val[array_keys($val)[0]][0],
                        'item_unit_id'      => $val[array_keys($val)[0]][1],
                    ]);
                }
            }

            DB::commit();
            Session::forget('token');
            $msg    = "Tambah" .$itm. " Berhasil";
            return redirect()->route('item.index')->with('msg', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            Session::forgot('token');
            $msg    = "Tambah" .$itm. " Gagal";
            return redirect()->route('item.add')->with('msg', $msg);
        }
    }

    public function editItem($item_id, $origin = null)
    {
        $paket = '';
        $pktitem = '';
        $counts = collect();
        $items = Session::get('items');
        $msg = '';
        $invtpaket = InvtItemPackge::where('item_id', $item_id)->get();
        $pkg = InvtItemPackge::where('package_item_id', $item_id)->get()->count();
        if($pkg){
            $msg = 'Ada paket yang menggunakan item ini';
        }
        if($invtpaket->count()){
            if(empty(Session::get('paket'))){
                foreach($invtpaket as $itm){
                    $arr = [$itm->package_item_id=>[$itm->item_quantity, $itm->item_unit_id]];
                    Session::push('paket', $arr);
                }
            }
            $pktitem = collect(Session::get('paket'));
            $unit = InvtItemUnit::get(['item_unit_id', 'item_unit_name']);
            foreach ($pktitem as $key => $val) {
                if(!$counts->contains(collect($val)->keys()[0])){
                    $counts->push(collect($val)->keys()[0]);
                }
            }
            $paket = InvtItem::with('category', 'merchant')->wherein('item_id', $counts)->get();
        }
        $unit = InvtItemUnit::get(['item_unit_id', 'item_unit_name']);

        $itemunits = InvtItemUnit::where('company_id', Auth::user()->company_id)
            ->get()
            ->pluck('item_unit_name', 'item_unit_id');
        $category  = InvtItemCategory::where('company_id', Auth::user()->company_id)
            ->get()
            ->pluck('item_category_name', 'item_catgeory_id');
        $merchant = SalesMerchant::where('company_id', Auth::user()->company_id)->get()->pluck('merchant_name', 'merchant_id');
        $data = InvtItem::where('item_id', $item_id)->first();
        $base_kemasan = 0;
        for ($n = 0; $n < 4; $n++) {
            $data['item_unit_id'.$n] != null ? $base_kemasan++ : '';
        }

        return view('content.InvtItem.Edit.index', compact('data', 'unit', 'itemunits', 'paket', 'pktitem', 'category', 'items', 'merchant', 'base_kemasan', 'counts', 'msg', 'pkg'));
    }

    public function processEditItem(Request $request, $origin = null)
    {
        $item = "Barang";
        $warehouse = InvtWarehouse::where('company_id', Auth::user()->company_id)
            ->where('merchant_id', $request->merchant_id)
            ->get();
        $fields = $request->validate([
            'item_id'             => 'required',
            'item_category_id'    => 'required|integer',
            'item_code'           => 'required',
            'item_name'           => 'required',
        ], [
            'item_category_id.integer'  => 'Wahana / Merchant Tidak Memiliki Kategori'
        ]);
        $paket = InvtItemPackge::where('item_id', $fields['item_id']);

        try {
        $merchant = SalesMerchant::find($request->merchant_id);
        $warehousecode = preg_replace('/[^A-Z]/', '', $merchant->merchant_name);
        if(!$warehouse->count()) {
            if($request->create_werehouse == 1) {
                InvtWarehouse::create([
                    'merchant_id'           => $request->merchant_id,
                    'warehouse_code'        => "GD{$warehouse}",
                    'warehouse_name'        => "Gudang {$merchant->merchant_name}",
                    'created_id'            => Auth::id(),
                    'company_id'            => Auth::user()->company_id
                ]);
            } else {
                return redirect()->route('item.add')->with('msg', 'Merchant Tidak Memiliki WareHouse, Harap Tambah Warehouse');
            }
        }
        DB::beginTransaction();
        $table                           = InvtItem::findOrFail($fields['item_id']);
        $packageitem                     = InvtItemPackge::with('unit')->where('package_item_id', $fields['item_id']);
        for ($l = 0; $l <= 4; $l++) {
            if($table['item_unit_id'.$l] != $request['item_unit_id'.$l]) {
                if($table['item_unit_id'.$l] !=null && $request['item_unit_id'.$l]==null) {
                    if($packageitem->where('item_unit_id', $table['item_unit_id'].$l)->get()->count()){
                        return redirect()->back()->withErrors('Ada Paket Yang Menggunakan Item"'.$table->item_name.'" Dengan Satuan "'.$packageitem->where('item_unit_id', $table['item_unit_id'.$l])->first()->unit->item_unit_name.'". Harap Tidak Menghapus Satuan Tersebut.');
                    }
                }
            }
        }
        foreach ($warehouse as $key => $val) {
            InvtItemStock::updateOrCreate([
                'company_id'            =>  Auth::user()->company_id,
                'item_id'               =>  $table['item_id'],
                'item_category_id'      =>  $table['item_category_id'],
                'warehouse_id'          =>  $val['warehouse_id'],
                'item_unit_id'          =>  $table['item_unit_id'],
            ], [
                'item_unit_id'          => $request->item_unit_id1
            ]);
        }
        $table->item_category_id         = $fields['item_category_id'];
        $table->item_code                = $fields['item_code'];
        $table->item_name                = $fields['item_name'];
        $table->merchant_id              = $request->merchant_id;
        $table->item_remark              = $request->item_remark;
        // *Kemasan
        $table->item_unit_id1            = $request->item_unit_id1;
        $table->item_default_quantity1   = $request->item_default_quantity1;
        $table->item_unit_price1         = $request->item_unit_price1;
        $table->item_unit_cost1          = $request->item_unit_cost1;
        $table->item_unit_id2            = $request->item_unit_id2;
        $table->item_default_quantity2   = $request->item_default_quantity2;
        $table->item_unit_price2         = $request->item_unit_price2;
        $table->item_unit_cost2          = $request->item_unit_cost2;
        $table->item_unit_id3            = $request->item_unit_id3;
        $table->item_default_quantity3   = $request->item_default_quantity3;
        $table->item_unit_price3         = $request->item_unit_price3;
        $table->item_unit_cost3          = $request->item_unit_cost3;
        $table->item_unit_id4            = $request->item_unit_id4;
        $table->item_default_quantity4   = $request->item_default_quantity4;
        $table->item_unit_price4         = $request->item_unit_price4;
        $table->item_unit_cost4          = $request->item_unit_cost4;
        $table->updated_id               = Auth::id();

        $paketarr = collect(Session::get('paket'));
        if($paket->count()&&empty(Session::get('paket'))){
            $itm = "Paket";
            $paket->delete();
        } else {
            $itm = "Paket";
            $paket->delete();
            foreach($paket as $val) {
                InvtItemPackge::create([
                    'item_id'           => $item['item_id'],
                    'package_item_id'   => array_keys($val)[0],
                    'item_quantity'     => $val[array_keys($val)[0]][0],
                    'item_unit_id'      => $val[array_keys($val)[0]][1],
                ]);
            }
        }

        if($table->save()) {
            DB::commit();
            $msg = "Ubah ".$itm." Berhasil";
            return redirect('/item')->with('msg', $msg);
        } else {
            $msg = "Ubah ".$itm." Gagal.";
            return redirect('/item')->with('msg', $msg);
        }
        } catch (\Exception $e) {
        DB::rollBack();
        dd($e);
        report($e);
        $msg = "Ubah ".$itm." Gagal";
        return redirect('/item')->with('msg', $msg);
        }
    }

    public function deleteItem($item_id)
    {
        try {
            DB::beginTransaction();
            InvtItem::find($item_id)->delete();
            DB::rollBack();
            return redirect()->route('item.index')->with(['msg' => 'Berhasil Menghapus Barang', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('item.index')->with(['msg' => 'Gagal Menghapus Barang', 'type' => 'danger']);
        }
    }

    public function getCategory(Request $request)
    {
        $items = Session::get('items');
        $items['item_category_id'] ?? $items['item_category_id'] = 1;
        $ctg = $items['item_category_id'];
        if ($request->from_paket) {
            $items['item_category_id'] ?? $items['item_category_id'] = 1;
            $ctg = $items['item_category_id'];
        }
        return response(ItemHelper::getCategory($ctg, $request));
    }

    public function addKemasan()
    {
        $items = Session::get('items');
        if (!$items || $items == '') {
            $items['item_code']             = '';
            $items['item_name']             = '';
            $items['item_barcode']          = '';
            $items['item_remark']           = '';
            $items['item_quantity']         = '';
            $items['item_price']            = '';
            $items['package_item_id']       = 1;
            $items['item_cost']             = '';
            $items['item_category_id']      = '';
            $items['kemasan']               = 1;
            $items['max_kemasan']           = 4;
        }
        $items['kemasan'] = $items['kemasan'] + 1;
        Session::put('items', $items);
    }
}