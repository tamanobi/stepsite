<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminGroupController extends Controller
{
    public function show(Request $request)
    {
        return view('admin.groups', ['groups' => \App\Models\Group::all()]);
    }
}
