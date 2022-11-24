<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Events\Me;

class UserAvatarController extends Controller
{
    public function upload_user_photo(Request $request)
    {
        $data = $request->all();
        $image_64 = $data['avatar']; //your base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);

        // check if user has an existing avatar
        if (auth()->user()->avatar != null) {
            // delete existing image file
            Storage::disk('user_avatars')->delete(auth()->user()->avatar);
        }

        $imageName = auth()->user()->id.'_'.date("YmdHis").'.'.$extension;
        Storage::disk('user_avatars')->put($imageName, base64_decode($image));

        // Update user's avatar column on 'users' table
        $profile = User::find(auth()->user()->id);
        $profile->avatar = $imageName;
        $user = User::where('id', auth()->user()->id)->update(['avatar' => $imageName]);

        // if ($profile->save()) {
        //     return response()->json([
        //         'status'    =>  'success',
        //         'message'   =>  'Profile Photo Updated!',
        //         'avatar_url'=>  url('storage/user-avatar/'.$imageName),
        //         'avatar_name'=>  $imageName
        //     ]);
        // } else {
        //     return response()->json([
        //         'status'    => 'failure',
        //         'message'   => 'failed to update profile photo!',
        //         'avatar_url'=> null
        //     ]);
        // }
        broadcast(new Me($profile));
        return response()->json($profile);

        // check if image has been received from form
        // if ($request->file('avatar')) {
        //     // check if user has an existing avatar
        //     if (auth()->user()->avatar != null) {
        //         // delete existing image file
        //         Storage::disk('user_avatars')->delete(auth()->user()->avatar);
        //     }
        //
        //     // processing the uploaded image
        //     $avatar_name = auth()->user()->id . '.' . $request->file('avatar')->getClientOriginalExtension();
        //     $avatar_path = $request->file('avatar')->storeAs('', $avatar_name, 'user_avatars');
        //
        //     // Update user's avatar column on 'users' table
        //     $profile = User::find(auth()->user()->id);
        //     $profile->avatar = $avatar_path;
        //     User::where('id', auth()->user()->id)->update(['avatar' => $avatar_path]);
        //
        //     if ($profile->save()) {
        //         return response()->json([
        //             'status'    =>  'success',
        //             'message'   =>  'Profile Photo Updated!',
        //             'avatar_url'=>  url('storage/user-avatar/'.$avatar_path)
        //         ]);
        //     } else {
        //         return response()->json([
        //             'status'    => 'failure',
        //             'message'   => 'failed to update profile photo!',
        //             'avatar_url'=> null
        //         ]);
        //     }
        // }
        //
        // return response()->json([
        //     'status'    => 'failure',
        //     'message'   => 'No image file uploaded!',
        //     'avatar_url'=> null
        // ]);
    }

    public function delete_user_photo(Request $request) {
      $data = $request->all();
      $variable = $data['avatar']; //your base64 encoded data
      // check if user has an existing avatar
      if (auth()->user()->avatar != null) {
          // delete existing image file
          Storage::disk('user_avatars')->delete(auth()->user()->avatar);
          User::where('id', auth()->user()->id)->update(['avatar' => $variable]);
          return response()->json("succes deleted avatar picture");
      }
    }
}
