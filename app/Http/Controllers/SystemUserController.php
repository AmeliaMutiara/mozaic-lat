<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoreSection;
use Illuminate\Http\Request;
use App\Models\SystemUserGroup;
use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SystemUserController extends Controller
{
    public function index(UserDataTable $table)
    {
        $systemuser = User::where('user_group_id', '!=', 1)
        ->where('company_id', Auth::user()->company_id)
        ->get();
        // dd($systemuser);

        return  $table->render('content.SystemUser.List.index', compact('systemuser'));
    }

    public function addSystemUser(Request $request)
    {
        $systemusergroup    = SystemUserGroup::where('company_id', Auth::user()->company_id)
        ->where('user_group_id', '!=', 1)
        ->get();
        // dd($systemusergroup);
        $coresection        = CoreSection::where('company_id', Auth::user()->company_id)
        ->get();

        return view('content.SystemUser.Add.index', compact('systemusergroup', 'coresection'));
    }

    public function processAddSystemUser(Request $request)
    {
        $fields = $request->validate([
            'username'          => 'required',
            'full_name'         => 'required',
            'password'          => 'required',
            'user_group_id'     => 'required',
        ],[
            'username.required'          => 'Nama Tidak Boleh Kosong',
            'full_name.required'         => 'Nama Panjang Tidak Boleh Kosong',
            'password.required'          => 'Password Tidak Boleh Kosong',
            'user_group_id.required'     => 'Harus Memilih Salah Satu User Group',
        ]);

        // dd($request->all());
        try {
            DB::beginTransaction();
            $user = User::create([
                'username'      => $fields['username'],
                'full_name'     => $fields['full_name'],
                'password'      => Hash::make($fields['password']),
                'phone_number'  => $request->phone_number,
                'user_group_id' => $fields['user_group_id'],
                'company_id'    => Auth::user()->company_id
            ]);
            DB::commit();
            return redirect()->route('user.index')->with(['msg' => 'Berhasil Menambahkan System User', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('user.add')->with(['msg' => 'Gagal Menambahkan System User', 'type' => 'danger']);
        }
    }

    public function editSystemUser($user_id)
    {
        $systemusergroup    = SystemUserGroup::where('company_id', Auth::user()->company_id)
        ->where('user_group_id', '!=', 1)
        ->get()
        ->pluck('user_group_name', 'user_group_id');
        $systemuser         = User::find($user_id);
        $coresection        = CoreSection::where('company_id', Auth::user()->company_id)
        ->get()
        ->pluck('section_name', 'section_id');
        $coresection[0]     = "Multi Section";
        dd($systemuser);
        return view('content.SystemUser.Edit.index', compact('systemusergroup', 'systemuser', 'coresection', 'user_id'));
    }

    public function processEditSystemUser(Request $request)
    {
        $fields = $request->validate([
            'user_id'           => '',
            'username'          => 'required',
            'full_name'         => 'required',
            'user_group_id'     => 'required',
        ],[
            'username.required'          => 'Nama Tidak Boleh Kosong',
            'full_name.required'         => 'Nama Panjang Tidak Boleh Kosong',
            'user_group_id.required'     => 'Harus Memilih Salah Satu User Group',
        ]);

        try {
            DB::beginTransaction();
            $user                    = User::findOrFail($fields['user_id']);
            $user->username          = $fields['username'];
            $user->full_name         = $fields['full_name'];
            $user->user_group_id     = $fields['user_group_id'];
            $user->phone_number      = $request->phone_number;
            $user->save();
            DB::commit();
            return redirect()->route('user.index')->with(['msg' => 'Berhasil Mengubah System User', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('user.edit')->with(['msg' => 'Gagal Mengubah System User', 'type' => 'danger']);
        }
    }

    public function deleteSystemUser($user_id)
    {
        try {
            DB::beginTransaction();
            User::find($user_id)->delete();
            DB::commit();
            return redirect()->route('user.index')->with(['msg' => 'Berhasil Menghapus System User', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('user.index')->with(['msg' => 'Gagal Menghapus System User', 'type' => 'danger']);
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

    public function changePassword($user_id)
    {
        return view('content.SystemUser.Change.index', compact($user_id));
    }

    public function processChangePassword(Request $request)
    {
        $request->validate([
            'password'      => 'required',
            'new_password'  => 'required',
        ],[
            'password.required'     => "Password Tidak Boleh Kosong",
            'new_password.required'     => "Password Baru Tidak Harus Diisi",
        ]);

        try {
            DB::beginTransaction();

            Hash::check($request->password, Auth::user()->password);

            User::find(auth()->user()->user_id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            DB::commit();
            return redirect()->route('user.index')->with(['msg' => 'Berhasil Mengubah Password System User', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e);
            return redirect()->route('user.changepw')->with(['msg' => 'Gagal Mengubah Password System User', 'type' => 'danger']);
        }
    }
}
