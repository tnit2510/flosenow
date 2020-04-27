<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user(); 
        $user = $this->createUser($getInfo, $provider); 
        $token = auth()->login($user); 

        return AuthController::respondWithToken($token);
    }

    private function createUser($getInfo, $provider){
        $user = User::whereProviderId($getInfo->id)->first();

        if (!$user) {
            $user = User::create([
                'username' => Str::random(10),
                'email'    => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id
            ]);
        }

        return $user;
    }
}
