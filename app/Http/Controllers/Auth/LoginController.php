<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $userId = $request->cookie('remember_user_id');
        $token = $request->cookie('remember_token');
        if ($userId && $token) {
            $user = User::where('id', $userId)
                ->where('remember_token', $token)
                ->where('is_delete', 0)
                ->first();

            if ($user) {
                Auth::login($user);
                session([
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_group' => $user->group_role,
                ]);

                Log::info('✅ Tự động đăng nhập từ cookie và lưu session', ['user_id' => $user->id]);
                return redirect('/productManager');
            }
        }

        return view('welcome');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255'
            ],
        ]);
        Log::info('Dữ liệu login:', $request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        Log::info('Dữ liệu validate hợp lệ');

        $user = User::where('email', $request->email)->first();
        if ($request->has('rememberMe')) {

            $token = bin2hex(random_bytes(32));
            $user->remember_token = $token;
            $user->save();
            cookie()->queue('remember_user_id', $user->id, 60 * 24 * 7);
            cookie()->queue('remember_token', $token, 60 * 24 * 7);
        }
        if (!$user) {
            Log::info('sai tài khoản');
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            Log::info('sai mật khẩu');
            return back()->withErrors(['password' => 'Mật khẩu không đúng.']);
        }

        if ($user->is_delete != 0) {
            Log::info('sai trạng thái');
            return back()->withErrors(['email' => 'Tài khoản không khả dụng.']);
        }

        Auth::login($user);
        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_group' => $user->group_role,
        ]);
        Log::info(' Đăng nhập thành công', ['user_id' => $user->id]);
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended('/productManager');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush(); // Xoá tất cả session

        cookie()->queue(cookie()->forget('remember_user_id'));
        cookie()->queue(cookie()->forget('remember_token'));

        return redirect('/');
    }
}
