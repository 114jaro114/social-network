<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\DB;
use Mail;
// use App\Mail\TestMail;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function register()
    {
        $validatedData = request()->validate([
            'name' => 'unique:users|required',
            'email' => 'unique:users|required',
            'password' => 'required'
        ]);

        $random = Str::random(60);

        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'status'  => 'offline',
            'account_verified' => $random
        ]);
        $user->save();

        $userData = array(
          'email' => $validatedData['email'],
          'name' => $validatedData['name'],
          'random' => $random,
          'link' => 'http://localhost:8080/activation-account/'.$random
        );

        Mail::send('emails.verification_account', $userData, function ($message) use ($userData) {
          $message->from('chatabysta@gmail.com', 'Activate account');
          $message->to($userData['email']);
          $message->subject('Actiovation link requested');
        });

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request()->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'error' => 'Bad credentials.'
            ], 401);
        } else {
          $user = DB::table('users')
            ->select('account_verified')
            ->where('id', auth()->user()->id)
            ->get();
          // $account_verified = User::select('account_verified')->where('id', auth()->user()->id)->get();
          if ($user[0]->account_verified == 'verified') {
            User::where('id', auth()->user()->id)->update(['status' => 'online']);
          } else if($user[0]->account_verified != 'verified') {
            return response()->json([
                'error' => 'Account is not verified.'
            ], 500);
          } else {
            return response()->json([
                'error' => 'Vyskytol sa nejaký problém. Skúste to prosím znova.'
            ], 403);
          }
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        User::where('id', auth()->user()->id)->update(['status' => 'offline']);

        Auth::logout();

        return response()->json(['msg' => 'Logged out.'], 200);
    }

    /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function me()
    {
      $me = DB::table('users')
                ->where("users.id", auth()->user()->id)
                ->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                ->get();
      // auth()->user()
      return response()->json($me);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'user' => auth()->user(),
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 201);
    }


    public function SocialSignup($provider)
     {
         // Socialite will pick response data automatic
         $user = Socialite::driver($provider)->stateless()->user();

         return response()->json($user);
     }

     public function callVerificationAccount(Request $request)
     {
         $user = User::where('account_verified', $request->token)->first();
         if (!$user) {
           return response()->json([
             'message' => 'Account was already activated/Invalid code/',
             'status_code' => 401
           ], 401);
         } else {
           $user->account_verified = 'verified';

           if ($user->save()) {
             return response()->json([
               'message' => 'Account verified successfully',
               'status_code' => 200
             ], 200);
           } else {
             return response()->json([
               'message' => 'Some error occurred, Please try again',
               'status_code' => 500
             ], 500);
           }
        }
     }

     /**
     * Send password reset link.
     */
    public function sendPasswordResetLink(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        if (!$user) {
          return response()->json([
            'message' => 'We have sent a verification code to your email address',
            'status_code' => 200
          ], 200);
        } else {
          // $from = array(
          //   'from' => 'chatabysta@gmail.com',
          //   'name' => $user->name,
          //   'verif_code' => rand(111111, 999999)
          // );
          // $from = 'chatabysta@gmail.com';
          // Mail::to('codebriefly@yopmail.com')->send(new TestMail($from));
          // return response()->json("cc");

          // $random = rand(111111, 999999);
          $random = Str::random(60);
          $user->verification_code = $random;

          if ($user->save()) {
            $userData = array(
              'email' => $user->email,
              'name' => $user->name,
              'random' => $random,
              'link' => 'http://localhost:8080/reset-password/'.$random
            );
            // Mail::to('codebriefly@yopmail.com')->send(new TestMail, $userData);
            Mail::send('emails.reset_password', $userData, function ($message) use ($userData) {
              $message->from('chatabysta@gmail.com', 'Password request');
              // $message->sender();
              $message->to($userData['email']);
              // $message->cc();
              // $message->bcc();
              // $message->replyTo();
              $message->subject('Reset password request');
              // $message->priority();
              // $message->attach();
            });
          }

          if (Mail::failures()) {
            return response()->json([
              'message' => 'Some error occurred, Please try again',
              'status_code' => 500
            ], 500);
          } else {
            return response()->json([
              'message' => 'We have sent a verification code to your email address',
              'status_code' => 200
            ], 200);
          }
        }
        // else {
        //   return response()->json([
        //     'message' => 'Some error occurred, Please try again',
        //     'status_code' => 500
        //   ], 500);
        // }
    }

    public function callResetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->where('verification_code', $request->token)->first();

        if (!$user) {
          return response()->json([
            'message' => 'User not found/Invalid code',
            'status_code' => 401
          ], 401);
        } else {

          $user->password = Hash::make($request->password);
          $user->verification_code = null;

          if ($user->save()) {
            return response()->json([
              'message' => 'Password updated successfully',
              'status_code' => 200
            ], 200);
          } else {
            return response()->json([
              'message' => 'Some error occurred, Please try again',
              'status_code' => 500
            ], 500);
          }
       }
    }

    public function getUserInfoById($id) {
      $userInfo = DB::table('users')
                    ->where("id", $id)
                    ->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar')
                    ->get();

      return response()->json($userInfo);
    }
}
