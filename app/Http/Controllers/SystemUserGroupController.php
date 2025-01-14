<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemMenu;
use Illuminate\Http\Request;
use App\Models\SystemUserGroup;
use App\Models\SystemMenuMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\DataTables\SystemUserGroupDataTable;

class SystemUserGroupController extends Controller
{
    public function index(SystemUserGroupDataTable $table)
    {
        $systemusergroup = SystemUserGroup::where('company_id', Auth::user()->company_id)
        ->where('user_group_id', '!=', 1)
        ->get();
        return  $table->render('content.SystemUserGroup.List.index', compact('systemusergroup'));
    }

    public function addSystemUserGroup(Request $request)
    {
        $systemmenu = SystemMenu::get();
        return view('content.SystemUserGroup.Add.index', compact('systemmenu'));
    }

    public function processAddSystemUserGroup(Request $request)
    {
        $fields = $request->validate([
            'user_group_name'       => 'required',
            'user_group_level'      => 'required',
        ]);

        $systemmenu = SystemMenu::get();

        $allrequest = $request->all();

        $usergroup = array(
            'user_group_name'       => $fields['user_group_name'],
            'user_group_level'      => $fields['user_group_level'],
            'company_id'            => Auth::user()->company_id
        );

        if(SystemUserGroup::create($usergroup)) {
            foreach($systemmenu as $key => $val) {
                if(isset($allrequest['checkbox_'.$val['id_menu']])){
                    $menumapping = array(
                        'user_group_level' => $fields['user_group_level'],
                        'id_menu'          => $val['id_menu'],
                        'company_id'       => Auth::user()->company_id
                    );
                    SystemMenuMapping::create($menumapping);
                }
            }
        } else {
            return redirect()->route('usergroup.add')->with(['msg' => 'Gagal Menambahkan System User Group', 'type' => 'success']);
        }

        $msg = 'Berhasil Menambahkan System User Group';
        return redirect()->route('usergroup.index')->with(['msg' => 'Berhasil Menambahkan System User Group', 'type' => 'success']);
    }

    public function editSystemUserGroup($user_group_id)
    {
        $systemusergroup = SystemUserGroup::where('user_group_id', $user_group_id)
        ->first();

        $systemmenu = SystemMenu::get();

        $systemmenumapping = SystemMenuMapping::where('user_group_level', $systemusergroup['user_group_level'])
        ->get();

        return view('content.SystemUserGroup.Edit.index', compact('systemusergroup', 'user_group_id', 'systemmenu'));
    }

    public function processEditSystemUserGroup(Request $request)
    {
        $fields = $request->validate([
            'user_group_id'             => 'required',
            'user_group_name'           => 'required',
            'user_group_level'          => 'required'
        ]);

        $systemmenu = SystemMenu::get();

        $allrequest = $request->all();

        $usergroup                   = SystemUserGroup::findOrFail($fields['user_group_id']);
        $user_group_level_last       = $usergroup['user_group_level'];
        $usergroup->user_group_name  = $fields['user_group_name'];
        $usergroup->user_group_level = $fields['user_group_level'];

        try {
            DB::beginTransaction();
            $usergroup->save();

            foreach($systemmenu as $key => $val){
                $menumapping_last = SystemMenuMapping::where('user_group_level', $user_group_level_last)
                ->where('id_menu', $val['id_menu'])
                ->first();

                if($menumapping_last){
                    $menumapping_last->delete();
                }

                if(isset($allrequest['checkbox_'.$val['id_menu']])){
                    $menumapping = array(
                        'user_group_level' => $fields['user_group_level'],
                        'id_menu'          => $val['id_menu'],
                    );
                    SystemMenuMapping::create($menumapping);
                }
            }

            DB::commit();
            return redirect()->route('usergroup.index')->with(['msg' => 'Berhasil Mengubah System User Group', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('usergroup.edit')->with(['msg' => 'Gagal Mengubah System User Group', 'type' => 'danger']);
        }
        
    }

    public function deleteSystemUserGroup($user_group_id)
    {
        try {
            DB::beginTransaction();
            SystemUserGroup::find($user_group_id)->delete();
            DB::commit();
            return redirect()->route('usergroup.index')->with(['msg' => 'Berhasil Menghapus System User Group', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('usergroup.index')->with(['msg' => 'Gagal Menghapus System User Group', 'type' => 'danger']);
        }
    }

    public function getUserGroupName($user_group_id)
    {
        $usergroupname = User::select('system_user_group.user_group_name')
        ->join('system_user_group', 'system_user_group.user_group_id', '=', 'system_user.user_group_id')
        ->where('system_user.user_group_id', '=', $user_group_id)
        ->first();

        return $usergroupname['user_group_name'];
    }

    public function getMenuMappingStatus($user_group_level, $id_menu)
    {
        $menumapping = SystemMenuMapping::where('user_group_level', $user_group_level)
        ->where('id_menu', $id_menu)
        ->count();

        return $menumapping;
    }
}
