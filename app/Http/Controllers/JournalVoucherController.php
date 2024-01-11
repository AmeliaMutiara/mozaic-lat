<?php

namespace App\Http\Controllers;

use App\DataTables\JournalVoucherDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class JournalVoucherController extends Controller
{
    public function index(JournalVoucherDataTable $table)
    {
        if(!$start_date = Session::get('start_date')) {
            $start_date = date('Y-m-d');
        } else {
            $start_date = Session::get('start_date');
        }

        if(!$end_date = Session::get('end_date')) {
            
        }
    }
}
