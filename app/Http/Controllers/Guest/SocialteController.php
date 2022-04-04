<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Socialite as SocialiteUser;
use Socialite;
use Hash;

class SocialteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfor = Socialite::driver($provider)->user();
        $user = $this->createOrGetUser($getInfor, $provider);
        if($user) {
            auth()->login($user);
            return redirect()->route('asbab.home');
        } else {
            return redirect()->route('asbab.home')->with(['flash_level'=>'danger','flash_message'=> 'Tài khoản của bạn không hoạt động.']);
        }
    }

    public function createOrGetUser($getInfor, $provider)
    {
        if(!$getInfor->getEmail()) {
            $user = null;
        } else {
            $getUser = SocialiteUser::whereProvider($provider)->whereProviderUserId($getInfor->getId())->first();
            if (!$getUser) {
                $user = User::where('email', $getInfor->getEmail())->first();
                if(!$user) {
                    $user = User::create([
                        'name' => $getInfor->getName(),
                        'email' => $getInfor->getEmail(),
                        'email_verified_at' => date('Y-m-d H:i:a'),
                        'type' => 1,
                        'password' => Hash::make('123456')
                    ]);
                    
                    $path = 'public/avatar/'.$user->id;
                    Storage::put($path, $getInfor->getAvatar());
    
                    $user->update([
                        'profile_photo_path' => $getInfor->getAvatar()
                    ]);
                } 
    
                SocialiteUser::create([
                    'provider' => $provider,
                    'provider_user_id' => $getInfor->getId(),
                    'user_id' => $user->id
                ]);
            } else {
                $user = $getUser->users;
            }
        }
        
        return $user;
    }
}
