<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', function () {
    return view('hoge', ['users' => \App\Models\User::all(), 'groups' => \App\Models\Group::all()]);
});
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/users', function () {
    $request_user = request()->users;
    $user_ids = array_keys(request()->users);
    $users = \App\Models\User::whereIn('id', $user_ids)->get();
    foreach ($users as $user) {
        $groups = $request_user[$user->id]['groups'] ?? null;
        $user->groups()->detach();
        if ($groups !== null) {
            $group_ids = array_map('intval', $groups);
            $user->groups()->attach($group_ids);
        }
        $user->save();
    }
    return redirect('/users');
});
