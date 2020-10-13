<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function show(Request $request)
    {
        return view('logout');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect('admin.users');
        }
    }
}
