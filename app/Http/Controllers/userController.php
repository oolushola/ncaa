<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\HttpResponse;
use Illuminate\Http\Request;
use Auth;
use App\updateHistory;


class userController extends Controller
{
    public function index()
    {
        return view('v1.ncaa.dashboard');
    }
    
    public function userRole()
    {
        if(Auth::check() && Auth::user()->role){
            $userslist = User::SELECT('id', 'name')->WHERENULL('role')->GET();
            $allUsersForPreview = User::SELECT('id', 'name', 'role')->PAGINATE(10);
            return view('v1.ncaa.gop.user.role', compact('userslist', 'allUsersForPreview'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function editUserRole($id){
        if(Auth::check() && Auth::user()->role){
            $userslist = User::SELECT('id', 'name')->GET();
            $allUsersForPreview = User::SELECT('id', 'name', 'role')->PAGINATE(10);
            $recid = User::findOrFail(base64_decode($id));
            return view('v1.ncaa.gop.user.role', compact('userslist', 'allUsersForPreview', 'recid'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function updateUserRole(Request $request, $id){
        if(Auth::check() && Auth::user()->role){
            $user = User::findOrFail($id);
            $role = $request->role; 
            $updateRole = DB::UPDATE(DB::RAW('UPDATE users SET role = '.$role.' WHERE id = '.$id.' '));
            return 'updated';
        }
        else{
            return redirect()->route('login');
        }
        
    }

    public function usersList()
    {
        if(Auth::check() && Auth::user()->role){
            $allusers = User::SELECT('name', 'phone', 'email', 'role', 'created_at')->PAGINATE(30);
            return view('v1.ncaa.gop.user.users', compact('allusers'));
        }
        return redirect()->route('login');        
    }

    public function profileUpdate()
    {
        if(Auth::check() && Auth::user()->role){
            return view('v1.ncaa.gop.user.profile-update');
        }
        return redirect()->route('login');        
    }

    public function updateProfile(Request $request, $id){
        if(Auth::check() && Auth::user()->role){
            $recid = User::findOrFail($id);
            $recid->UPDATE($request->all());

            if($request->hasFile('file')){
                $fullname = $request->file('file');
                $name = str_slug($request->get('name')).'.'.$fullname->getClientOriginalExtension();
                $destination_path = public_path('confidentials/users/');
                $usersPath = $destination_path."/".$name;
                $fullname->move($destination_path, $name);
                $recid->photo = $name;
                $recid->save();
            }
            return 'updated';
        }
        return redirect()->route('login');
    }

    public function activitylogbymodules($module){
        if(Auth::check() && Auth::user()->role){
            $moduleActivityLogs = updateHistory::SELECT('record_id', 'module', 'actual')->DISTINCT()->WHERE('module', $module)->ORDERBY('updated_at', 'DESC')->GET();
            return view('v1.ncaa.activity-log-module', compact('moduleActivityLogs', 'module'));
        }
        return redirect()->route('login');
    }

    public function activitylogbyactual($module, $actual, $id){
        if(Auth::check() && Auth::user()->role){
            
            $threadlists = DB::SELECT(DB::RAW('SELECT a.*, b.photo FROM tbl_ncaa_update_histories a JOIN users b ON a.name = b.name  WHERE record_id = '.$id.' AND module = "'.$module.'" ORDER BY updated_at DESC'));
            
            $moduleActivityLogs = updateHistory::SELECT('record_id', 'module', 'actual')->DISTINCT()->WHERE('module', $module)->WHERE('record_id', '<>', $id)->ORDERBY('updated_at', 'DESC')->GET();

            return view('v1.ncaa.activity-log-thread', compact('threadlists', 'module', 'actual', 'moduleActivityLogs'));
        }
        return redirect()->route('login');        
    }

}
