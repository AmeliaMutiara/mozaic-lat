<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\Auth;

class SystemUserController extends Controller
{
    public function index(UserDataTable $table)
    {
        $systemuser = User::where('user_group_id', '!=', 1)
        ->where('company_id', Auth::user()->company_id)
        ->get();

        return  $table->render('content.SystemUser.List.index', compact('systemuser'));
    }

    public function addSystemUser(Request $request)
    {
        
    }
}
