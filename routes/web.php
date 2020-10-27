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
Route::prefix('admin')->group(function () {
    Route::get('users', 'AdminUserController@show')->name('admin.users');
    Route::get('groups', 'AdminGroupController@show')->name('admin.groups');
});
Route::get('/login', 'LoginController@show')->name('login');
Route::post('/login', 'LoginController@authenticate');
Route::get('/logout', 'LogoutController@show')->name('logout');
Route::post('/logout', 'LogoutController@logout');
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
// LINEの認証画面に遷移
Route::get('auth/line', 'Auth\LineOAuthController@redirectToProvider')->name('line.login');
// 認証後にリダイレクトされるURL(コールバックURL)
Route::get('auth/line/callback', 'Auth\LineOAuthController@handleProviderCallback');
