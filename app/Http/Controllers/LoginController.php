<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

date_default_timezone_set('Asia/Bangkok');

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {

        $credentials = $this->validate($request, [
            'nip' => 'required|digits:9|exists:users,nip',
            'password' => 'required',
        ]);

        // dd("berhasil login");

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        // if (Auth::guard('web')->attempt(['nip' => $request->nip, 'password' => $request->password])) {
        //     return redirect('/');
        // }
        // $request->session()->flash('login_error', 'NIP or Password is invalid :(');
        return back()->with('login_error', 'Galat login. NIP atau Password mungkin salah');
    }


    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        // Auth::guard('web')->logout();
        return redirect('/login');
    }
};
