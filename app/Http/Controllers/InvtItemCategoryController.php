<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\InvtItemCategoryDataTable;
use App\Models\InvtItemCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InvtItemCategoryController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

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
        if(!$datacategory || $datacategory = ''){
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
            'item_category_remark'  => ''
        ]);

        $data = InvtItemCategory::create([
            'item_category_code'    => $fields['item_category_code'],
            'item_category_name'    => $fields['item_category_name'],
            'item_category_remark'  => $fields['item_category_remark'],
            'margin_precentage'     => $request['margin_percentage'],
            'company_id'            => Auth::user()->company_id,
            'updated_id'            => Auth::id(),
            'created_id'            => Auth::id()
        ]);

        if($data->save()){
            $msg = 'Tambah Kategori Berhasil';
            return redirect('/item-category/add')->with('msg', $msg);
        } else {
            $msg = 'Tambah Kategori Gagal';
            return redirect('/item-category/add')->with('msg', $msg);
        }
    }

    // public function editItemCategory($item_catgory_id)
    // {

    // }
}
