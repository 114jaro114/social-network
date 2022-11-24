<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class OAuthController extends Controller
{
  /**
   * Obtain the user information from the provider.
   *
   * @param  string $driver
   * @return \Illuminate\Http\Response
   */
   public function handleProviderCallback($provider)
     {
         $user = Socialite::driver($provider)->stateless()->user();

         /* HERE CREATE USER WITH YOUR APP LOGIC. If email is unique... */

         // Login the created user
         // Auth::login($user, true);
         // $credentials = [];
         // array_push($credentials, $user->name);
         // array_push($credentials, 'heslo');
         // Get the username (or wathever you want to return in the JWT).
         $success['name'] = $user->name;
         $success['email'] = $user->email;
         // Create a new access_token for the session (Passport)
         // $token = Auth::attempt($credentials);
         // $success['token'] = $token;

         ////////
         // $user = Socialite::driver($provider)->stateless()->user();

         // return response()->json($user);
         // return redirect('http://localhost:8080/register')->with('info', $user);
         // return view('welcome');
         /////////
 // $success['token'] = Auth::user()->createToken('MyApp')->accessToken;
         // Create new view (I use callback.blade.php), and send the token and the name.
         return view('welcome', [
             'name' => $success['name'],
             'email' => $success['email'],
         ]);
     }
}
