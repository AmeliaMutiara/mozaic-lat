<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\InvtItemCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\DataTables\InvtItemCategoryDataTable;
class InvtItemCategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(InvtItemCategoryDataTable $table)
    {
        Session::forget('datacategory');
        $data = InvtItemCategory::select('item_category_code', 'item_category_name', 'item_category_id')
            // ->where('data_state', 0)
            ->where('company_id', Auth::user()->company_id)
            ->get();
        return  $table->render('content.InvtItemCategory.List.index');
    }
    public function addItemCategory()
    {
        $datacategory = Session::get('datacategory');
        return view('content.InvtItemCategory.Add.index', compact('datacategory'));
    }
    public function elementsAddItemCategory(Request $request)
    {
        $datacategory = Session::get('datacategory');
        if (!$datacategory || $datacategory = '') {
            $datacategory['item_category_code']     = '';
            $datacategory['item_category_name']     = '';
            $datacategory['margin_percentage']      = '';
            $datacategory['item_category_remark']   = '';
        }
        $datacategory[$request->name] = $request->value;
        Session::put('datacategory', $datacategory);
    }
    public function addReset()
    {
        Session::forget('datacategory');
        return redirect()->back();
    }
    public function processAddItemCategory(Request $request)
    {
        $fields = $request->validate([
            'item_category_code'    => 'required',
            'item_category_name'    => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = InvtItemCategory::create([
                'item_category_code'    => $fields['item_category_code'],
                'item_category_name'    => $fields['item_category_name'],
                'item_category_remark'  => $request->item_category_remark,
                'margin_precentage'     => $request->margin_percentage,
                'company_id'            => Auth::user()->company_id
            ]);
            DB::commit();
            return redirect()->route('ic.index')->with('msg', 'Berhasil Menambahkan Kategori Barang');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('ic.add')->with('msg', 'Gagal Menambahkan Kategori Barang');
        }
    }
    public function editItemCategory($item_category_id)
    {
        $data = InvtItemCategory::select('item_category_code', 'item_category_name', 'item_category_id', 'item_category_remark', 'margin_percentage')
        ->where('item_category_id', $item_category_id);
        // ->first();
        return view('content.InvtItemCategory.Edit.index', compact('data'));
    }
    public function processEditItemCategory(Request $request)
    {
        $fields = $request->validate([
            'category_id'       => '',
            'category_code'     => 'required',
            'category_name'     => 'required',
            'category_remark'   => ''
        ]);
        try {
            DB::beginTransaction();
            $table                          = InvtItemCategory::findOrFail($fields['category_id']);
            $table->item_category_code      = $fields['category_code'];
            $table->item_category_name      = $fields['category_name'];
            $table->item_category_remark    = $fields['category_remark'];
            $table->margin_precentage       = $request['margin_percentage'] == '' ? 0 : $request['margin_percentage'];
            $table->updated_id              = Auth::id();
            DB::commit();
            return redirect()->route('ic.index')->with('msg', 'Berhasil Mengubah Kategori Barang');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('ic.edit')->with('msg', 'Gagal Mengubah Kategori Barang');
        }
    }
    public function deleteItemCategory($item_category_id)
    {
        $table              = InvtItemCategory::findOrFail($item_category_id);
        // $table->data_state  = 1;
        $table->updated_id  = Auth::id();

        if($table->save()){
            $msg = "Berhasil Menghapus Kategori Barang";
            return redirect('/item-category')->with('msg', $msg);
        } else {
            $msg = "Gagal Menghapus Kategori Barang";
            return redirect('/item-category')->with('msg', $msg);
        }
    }
}