<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Imports\CreatePermissionImport;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ImageIntervention;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }


    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();

        $userInfo = UserInfo::where('user_id', $user->id)->first();
        if ($request->file()) {
            $image = $this->imageService->storeImage($request->file('avatar'), 'public/images/avatar', $userInfo->avatar, $user->username);
            $userInfo->avatar = $image;
            $userInfo->save();
        
        }

        return statusResponse('200',200, 'Cập nhật thành công!', '');
    }

    public function show()
    {
        $user = Auth::user();
        $userInfo = UserInfo::where('user_id', $user->id)->first();
        $userInfo->avatar = '/storage/images/avatar/'.$userInfo->avatar;
        $userInfo->username = $user->username;
        $userInfo->email = $user->email;
        return $userInfo;
    }

    public function update(Request $request)
    {
        $user_id = Auth::user()->id;
        $userInfo = UserInfo::where('user_id', $user_id)->first();     
        $userInfo->name = $request->name;
        $userInfo->phone = $request->phone;
        $userInfo->dob = $request->dob;
        $userInfo->adress = $request->adress;
        $userInfo->save();
        return statusResponse('200',200, 'Cập nhật thành công!', '');
    }

    public function avatar(request $request)
    {
        
        $user = Auth::user();

        $userInfo = UserInfo::where('user_id', $user->id)->first();

        $image =  $this->imageService->storeImage($request->file('avatar'), 'public/images/avatar', $userInfo->avatar, $user->username);

        $userInfo->avatar = $image;
        $userInfo->save();
        return statusResponse('200',200, 'Cập nhật thành công!', '');

    }

    public function addRoleTo(request $request)
    {
        if ($request->user()->can('add-role-to-user')) {

            $User = User::findOrFail($request->user_id);        
            $User->roles()->attach($request->role_id);

            $role = Role::findOrFail($request->role_id); 

            $permissions_user = DB::table('users_permissions')->where('user_id', $request->user_id)->pluck('permission_id')->toArray();
            $permissions_role = $role->permissions->pluck('id')->toArray();
            $diffArray = array_values(array_diff($permissions_role, $permissions_user));
            
            $User->permissions()->attach($diffArray);
            return statusResponse('200',200, 'Cập nhật thành công!', '');

        }
        return statusResponse('200',401, 'Bạn không có quyền truy cập!', '');
    }

    public function addPermissonsTo(request $request)
    {
        if ($request->user()->can('add-permisson-to-user')) {
            $User = User::findOrFail($request->user_id);        
            $User->permissions()->attach($request->permission_id);
            return statusResponse('200',200, 'Cập nhật thành công!', '');
        }
        return statusResponse('200',401, 'Bạn không có quyền truy cập!', '');
    }

    public function addManyPermissonsTo(request $request)
    {
        if ($request->user()->can('add-many-permisson-to-user')) {

            $User = User::findOrFail($request->user_id);        
            $User->givePermissionsTo($request->permission_id);
            
            return statusResponse('200',200, 'Cập nhật thành công!', '');
        }
        return statusResponse('200',401, 'Bạn không có quyền truy cập!', '');
    }

    public function addPermissonsToRole(request $request)
    {
        if ($request->user()->can('add-permisson-to-role')) {
            $role = Role::findOrFail($request->role_id);        
            $role->permissions()->attach($request->permission_id);
            
            return statusResponse('200',200, 'Cập nhật thành công!', '');
        }
        return statusResponse('200',401, 'Bạn không có quyền truy cập!', '');
    }
    
    public function createRolePermission(Request $request){
        // if ($request->user()->can('upload-excel-create-role-permission')) {
            Excel::import(new CreatePermissionImport(), storage_path('eleaning-role-permission.xlsx'));
            return statusResponse('200',200, 'Cập nhật thành công!', '');
        // }
        return statusResponse('200',401, 'Bạn không có quyền truy cập!', '');
    }
    
    
}
