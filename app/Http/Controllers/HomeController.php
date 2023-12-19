<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SystemLogUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $menus =  User::select('system_menu_mapping.*','system_menu.*')
        // ->join('system_user_group','system_user_group.user_group_id','=','system_user.user_group_id')
        // ->join('system_menu_mapping','system_menu_mapping.user_group_level','=','system_user_group.user_group_level')
        // ->join('system_menu','system_menu.id_menu','=','system_menu_mapping.id_menu')
        // ->where('system_user.user_id','=',Auth::id())
        // // ->where('system_menu_mapping.company_id', Auth::user()->company_id)
        // ->orderBy('system_menu_mapping.id_menu','ASC')
        // ->get();
        $menus = [];
        return view('home',compact('menus'));
    }
    public function testing()
    {
        print_r('TES');
        exit;
    }
	public function set_log($user_id, $username, $id, $class, $pk, $remark){

		date_default_timezone_set("Asia/Jakarta");

		$log = array(
			'user_id'		=>	$user_id,
			'username'		=>	$username,
			'id_previllage'	=> 	$id,
			'class_name'	=>	$class,
			'pk'			=>	$pk,
			'remark'		=> 	$remark,
			'log_stat'		=>	'1',
			'log_time'		=>	date("Y-m-d G:i:s")
		);
		return SystemLogUser::create($log);
	}
	public static function quote(){
        $quotes = collect(json_decode(Storage::get('public/quotes.min.json')))->random();
        return $quotes->quote.' - '.$quotes->by;
	}
}
