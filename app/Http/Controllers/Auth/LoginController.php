<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = \DB::table('users')->select('anggota_keluarga.is_active', 'anggota_keluarga.email')->join('anggota_keluarga', 'anggota_keluarga.anggota_keluarga_id', 'users.anggota_keluarga_id')->where('anggota_keluarga.email', $input['username'])->first();
        
        if (empty($user)) {
            $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            if(auth()->attempt(array($fieldType => strtolower($input['username']), 'password' => $input['password'])))
            {
                return redirect()->route('landing.index');
            }else{
                return redirect()->route('login')
                    ->with('error','Username atau Password yang anda masukan salah!');
            }
        } else {
            if ($user->is_active == true) {
                $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
                if(auth()->attempt(array($fieldType => strtolower($input['username']), 'password' => $input['password'])))
                {
                    return redirect()->route('landing.index');
                }else{
                    return redirect()->route('login')
                        ->with('error','Username atau Password yang anda masukan salah!');
                }
            } else {
                return redirect()->route('login')
                        ->with('error','Status akun anda tidak aktif, silahkan hubungi Ketua RT setempat');
            }
        }
    }
}
