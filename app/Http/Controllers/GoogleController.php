<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(){
        $user = Socialite::driver('google')->user();
        $findUser = User::where('google_id' , $user->id)->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect()->intended('home');
        }else{

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => Hash::make('12345678')
            ]);

            Auth::login($newUser);
            return redirect()->intended('home');
        }
    }
}
