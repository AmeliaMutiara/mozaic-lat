<?php

namespace App\Http\Controllers;

use App\DataTables\ChildDataTable;
use App\DataTables\ParentDataTable;
use App\Models\ParentTable;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function index(ParentDataTable $table) {
       return  $table->render('content.ContohTable.List.index');
    }
    public function child($parent_id,ChildDataTable $table) {
        $parent = ParentTable::find($parent_id);
        return $table->with(['parent_id'=>$parent_id])->render('content.ContohTable.Detail.index',compact('parent'));
    }
}
