<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $pago   = Business::where('id', 1)->first()->pago;
        if($pago != 1)
            return view('not_authorized');
        return view('login');
    }

    public function login(Request $request)
    {
        $user       = trim($request->input('user'));
        $password   = trim($request->input('password'));

        if(empty($user) || empty($password))
        {
            return back()->with('message','El campo usuario es obligatorio.');
        }

        $credentials =
        [
            'user'      => strtolower($user),
            'password'  => $password
        ];

        if(Auth::attempt($credentials))
        {
            $status   = User::where('id'  , Auth::user()->id)->first()->estado;
            if($status == '1') // Si está activo
            {
                $request->session()->regenerate();
                return redirect()->route('admin.home')->with('message_welcome','El campo usuario es obligatorio.');
            }

            else
            {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('message','No tiene los permisos necesarios');

            }
        }
        else
        {
            Auth::logout();
            return back()->with('message','Estas credenciales no coinciden con nuestros registros.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
