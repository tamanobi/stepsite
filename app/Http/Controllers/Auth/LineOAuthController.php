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
    private const LINE_OAUTH_URL = 'https://access.line.me/oauth2/v2.1/authorize?';
    private const LINE_TOKEN_API_URL = 'https://api.line.me/oauth2/v2.1/';
    private const LINE_PROFILE_API_URL = 'https://api.line.me/v2/';
    private $client_id;
    private $client_secret;
    private $callback_url;

    public function __construct() {
        $this->client_id = Config('line.client_id');
        $this->client_secret = Config('line.client_secret');
        $this->callback_url = Config('line.callback_url');
    }

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

    private function fetchUserInfo($access_token)
    {
        $base_uri = ['base_uri' => self::LINE_PROFILE_API_URL];
        $method = 'GET';
        $path = 'profile';
        $headers = ['headers' => 
            [
                'Authorization' => 'Bearer ' . $access_token
            ]
        ];
        $user_info = $this->sendRequest($base_uri, $method, $path, $headers);
        return $user_info;
    }

    private function fetchTokenInfo($code)
    {
        $base_uri = ['base_uri' => self::LINE_TOKEN_API_URL];
        $method = 'POST';
        $path = 'token';
        $headers = ['headers' => 
            [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ];
        $form_params = ['form_params' => 
            [
                'code'          => $code,
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->callback_url,
                'grant_type'    => 'authorization_code'
            ]
        ];
        $token_info = $this->sendRequest($base_uri, $method, $path, $headers, $form_params);
        return $token_info;
    }

    private function sendRequest($base_uri, $method, $path, $headers, $form_params = null)
    {
        try {
            $client = new Client($base_uri);
            if ($form_params) {
                $response = $client->request($method, $path, $form_params, $headers);
            } else {
                $response = $client->request($method, $path, $headers);
            }
        } catch(\Exception $ex) {
            //　例外処理
        }
        $result_json = $response->getbody()->getcontents();
        $result = json_decode($result_json);
        return $result;
    }
}
