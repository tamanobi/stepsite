<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class LineOAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('line')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $providerUser = Socialite::driver('line')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', $e->getMessage());
        }

        $account = \App\Models\LinkedSocialAccount::where('provider_name', 'line')
            ->where('provider_id', $providerUser->getId())->first();
        if ($account) {
            $authUser = $account->user;
        } else {
            $user = \App\Models\User::create([
                'name' => $providerUser->getName(),
                'email' => Str::random(20) . '@example.com',
                'password'=> Hash::make(Str::random(20)),
            ]);
            $user->accounts()->create([
                'provider_name' => 'line',
                'provider_id' => $providerUser->getId(),
            ]);

            $authUser = $user;
        }

        Auth::login($authUser, true);
        return redirect()->to('/admin/groups');
    }
}
