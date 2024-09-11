<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\Notifications\EmailVerificationNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Otp;
use DB;
use Illuminate\Support\Str;
use stdClass;





class RegisterControler extends Controller
{
    /**

     *  @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Account"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="username", type="string", format="text", example="congvinh"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="")
     * )
     */

    public function login(LoginRequest $request)
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {


            $user = User::where('username', $request->username)->first();

            $token = $user->createToken('App')->accessToken;
            $role = $user->roles->pluck('slug');
            $permission = $user->permissions->pluck('slug');

            $data = [
                'token' => $token,
                'role' => $role,
                'permission' => $permission,
                'user_id' => $user->id
            ];

            if (!$user->email_verified_at) {
                $this->resendOtp();
                return statusResponse('ACC013', 200, 'Bạn chưa xác thực!', $data);
            }

            $name_user = UserInfo::where('user_id', $user->id)->first()->name;
            if (!$name_user) {
                return statusResponse('ACC017', 200, 'Bạn chưa cập nhật thông tin!', $data);
            }

            return statusResponse(200, 200, 'Đăng nhập thành công!', $data);
        }

        return statusResponse(200, 400, 'Tên đăng nhập hoặc mật khẩu không chính xác', '');
    }


    /**
     *  @OA\Post(
     *     path="/api/register",
     *     operationId="register",
     *     tags={"Account"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"username","email","password"},
     *             @OA\Property(property="username", type="string", format="text", example="congvinh"),
     *             @OA\Property(property="email", type="string", format="text", example="congvinh@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     */

    public function register(RegisterRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = Hash::make($request->password);
        $user->save();

        $userInfo = new UserInfo();
        $userInfo->user_id = $user->id;
        $userInfo->save();

        // if ($request->type == 1) {
        //     $user->roles()->attach(2);
        //     $role = Role::findOrFail(2);
        // } else {
        $user->roles()->attach(2);
        $role = Role::findOrFail(2);
        // }
        // $permissions_role = $role->permissions->pluck('id');
        // $user->permissions()->attach($permissions_role);

        $user->notify(new EmailVerificationNotification());

        $token = $user->createToken('App')->accessToken;
        $role = $user->roles->pluck('slug');
        // $permission = $user->permissions->pluck('slug');


        return response()->json([
            'statusCode' => 200,
            'data' => [
                'token' => $token,
                'role' => $role,
                // 'permission' => $permission
            ]
        ], 200);
        return response()->json($user);
    }

    /**
     *  @OA\Post(
     *     path="/api/resendOtp",
     *     operationId="resendOtp",
     *     tags={"Account"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *        
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     */
    public function resendOtp()
    {
        $email = Auth::user()->email;
        $user = User::where('email', $email)->first();
        $user->notify(new EmailVerificationNotification());
        return statusResponse('200',200, 'Gửi OTP thành công!', '');
    }

    /**
     *  @OA\Post(
     *     path="/api/verifyOtp",
     *     operationId="verifyOtp",
     *     tags={"Account"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"otp"},
     *             @OA\Property(property="otp", type="string", format="text", example=""),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     */
    public function verifyOtp(Request $request)
    {
        $email = Auth::user()->email;

        $otps = DB::table('otps')->where('identifier', $email)->first();

        // $user_checkCreate = ;
        $created_at = Carbon::parse($otps->created_at);
        $two_minutes_later = $created_at->addMinutes(2);
        $current_time = Carbon::now();

        if ($current_time->greaterThan($two_minutes_later)) {
            return statusResponse('200',400, 'Otp sai hoặc đã hết hạn!', '');
        }

        $user_otp = $otps->token;
        if ($request->otp != $user_otp) {
            return statusResponse('200',400, 'Otp sai hoặc đã hết hạn!', '');
        }

        $user = User::where('email', $email)->first();
        $user->email_verified_at = time();
        $user->save();
        return statusResponse('200',200, 'Cập nhật thành công!', '');
    }


    /**
     *  @OA\Post(
     *     path="/api/changePassword",
     *     operationId="changePassword",
     *     tags={"Account"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"oldPassword", "newPassword"},
     *             @OA\Property(property="oldPassword", type="string", format="text"),
     *             @OA\Property(property="newPassword", type="string", format="text"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->oldPassword, $user->password)) {
            $userAcc = User::where('id', $user->id)->first();
            $userAcc->password = Hash::make($request->newPassword);
            $userAcc->save();
            return statusResponse('200',200, 'Cập nhật thành công!', '');
        } else {
            return statusResponse('200',404, 'Mật khẩu không chính xác!', '');
        }

    }

    /**
     *  @OA\Post(
     *     path="/api/forgotPassword",
     *     operationId="forgotPassword",
     *     tags={"Account"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"email"},
     *             @OA\Property(property="email", type="string", format="email"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *     )
     * )
     */
    public function forgotPassword(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return statusResponse('404', 200, 'Email không tồn tại!', '');

        }
        $user->notify(new EmailVerificationNotification());
        return statusResponse('200', 200, 'Gửi OTP thành công!', '');
    }


    public function getUser(Request $request)
    {
        return response()->json($request->user('api'));
    }
}
