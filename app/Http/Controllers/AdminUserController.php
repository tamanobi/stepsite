<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function show(Request $request)
    {
        $email = null;
        if (Auth::check()) {
            $email = Auth::user()->email;
        }
        return view('hoge', ['email' => $email,'users' => \App\Models\User::all(), 'groups' => \App\Models\Group::all()]);
    }
}
