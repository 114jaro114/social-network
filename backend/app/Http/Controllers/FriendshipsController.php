<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friendship;
use App\Models\User;
use App\Events\Friendships;
use App\Events\Friendship_remove;
use Illuminate\Support\Facades\DB;

// use App\Traits\Friendable;

class FriendshipsController extends Controller
{
    public function getFriendships()
    {
        $query = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.user_requested')
                                         ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                         ->where('requester', '=', auth()->user()->id)
                                         ->where('friendship_status', '1')
                                         ->get();
        $query2 = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.requester')
                                         ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                         ->where('user_requested', '=', auth()->user()->id)
                                         ->where('friendship_status', '1')
                                         ->get();

        $friendsAll = $query->merge($query2)->all();
        return response()->json($friendsAll);
    }

    public function getFriendshipsProfile($id) {
      $query = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.user_requested')
                                       ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                       ->where('requester', '=', $id)
                                       ->where('friendship_status', '1')
                                       ->get();
      $query2 = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.requester')
                                       ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                       ->where('user_requested', '=', $id)
                                       ->where('friendship_status', '1')
                                       ->get();

      $friendsAll = $query->merge($query2)->all();
      return response()->json([$friendsAll, count($friendsAll)]);
    }

    public function getAllFriendshipRequests()
    {
       $query = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.user_requested')
                                        ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                        ->where('friendships.requester', '=', auth()->user()->id)
                                        ->where('friendship_status', '2')
                                        ->get();
       $query2 = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.requester')
                                        ->select('friendships.*', 'users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                                        ->where('friendships.user_requested', '=', auth()->user()->id)
                                        ->where('friendship_status', '2')
                                        ->get();
       $allFriendshipRequests = json_decode( json_encode($query->merge($query2)->all()), true);
       return response()->json($allFriendshipRequests);
    }

    public function sendFriendshipRequest(Request $request)
    {
        // print_r($request);
        if (Friendship::where('requester', '=', auth()->user()->id)->where('user_requested', '=', $request->user_requested)->exists()) {
            return response()->json('uz existuje');
        } elseif (Friendship::where('requester', '=', $request->user_requested)->where('user_requested', '=', auth()->user()->id)->exists()) {
            return response()->json('uz existuje2');
        } else {
            $friendship = Friendship::create([
              'requester' => auth()->user()->id,
              'user_requested' => $request->user_requested,
              'friendship_status' => $request->friendship_status
            ]);

            broadcast(new Friendships($friendship->load('fromUser')));
        }

        return response()->json($friendship);
    }

    public function checkIfUserIsMyFriend($id)
    {
        if ($id == auth()->user()->id) {
          $userData = DB::table('users')->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                         ->where('id', '=', auth()->user()->id)
                         ->get();
          return response()->json([-1, $userData]);
        } else {
          $checkIfUserIsMyFriend = DB::table('friendships')->where([['requester', '=', $id],
                                                                   ['user_requested', '=', auth()->user()->id],
                                                                   ['friendship_status', '=', '1']])
                                                          ->orWhere([['requester', '=', auth()->user()->id],
                                                                     ['user_requested', '=', $id],
                                                                     ['friendship_status', '=', '1']])
                                                          ->get();

          if (count($checkIfUserIsMyFriend) == 0) {
           $userData = DB::table('users')->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                            ->where('id', '=', $id)
                            ->get();
          } else if ($checkIfUserIsMyFriend[0]->requester == auth()->user()->id) {
            $userData = DB::table('users')->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                             ->where('id', '=', $checkIfUserIsMyFriend[0]->user_requested)
                             ->get();
          } else {
            $userData = DB::table('users')->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')
                             ->where('id', '=', $checkIfUserIsMyFriend[0]->requester)
                             ->get();
          }
          return response()->json([count($checkIfUserIsMyFriend), $userData, count($userData)]);
        }
    }

    public function acceptFriendshipRequest(Request $request)
    {
        $friendship = DB::table('friendships')
                         ->where('requester', '=', $request->requester)
                         ->where('user_requested', '=', auth()->user()->id)
                         ->update(['friendship_status' => $request->friendship_status]);
        return response()->json($friendship);
    }

    public function refuseFriendshipRequest(Request $request)
    {
        $friendship = DB::table('friendships')
                        ->where([
                                ['requester', '=', $request->requester],
                                ['user_requested', '=', $request->user_requested]
                        ])->delete();
        return response()->json($friendship);
    }


    public function removeFromFriendshipList(Request $request)
    {
        DB::table('friendships')
        ->where([
                ['requester', '=', $request->requester],
                ['user_requested', '=', $request->user_requested]
        ])
         ->delete();
         $friendship = (object) array('requester' => $request->requester, 'user_requested' => $request->user_requested);
         broadcast(new Friendship_remove($friendship));
        return response()->json($friendship);
    }

    public function getCountMyFriendshipRequests()
    {
        $countMyFriendshipRequests = DB::table('friendships')
                                        ->where([['user_requested', '=', auth()->user()->id], ['friendship_status', '=', '2']])
                                        ->count();
        return response()->json($countMyFriendshipRequests);
    }

    public function getAllPossibleFriends()
    {
        $query = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.user_requested')
                                         ->select('users.id')
                                         ->where('friendships.requester', '=', auth()->user()->id)
                                         ->get();
        $query2 = DB::table('friendships')->join('users', 'users.id', '=', 'friendships.requester')
                                         ->select('users.id')
                                         ->where('friendships.user_requested', '=', auth()->user()->id)
                                         ->get();
        $friendsAll = json_decode( json_encode($query->merge($query2)->all()), true);

        array_push($friendsAll, auth()->user()->id);

        $allUsersWithoutFriendsAndMe =  User::whereNotIn('id', $friendsAll)->select('users.id', 'users.name', 'users.email', 'users.status', 'users.avatar', 'users.created_at')->get();

        return response()->json($allUsersWithoutFriendsAndMe);
    }

    public function contactForm(Request $request)
    {
        $surname = $request->surname;
        $lastname =  $request->lastname;
        $address = $request->address;
        $city = $request->city;
        $postcode = $request->postcode;
        $country = $request->country;
        $phone = $request->phone;

        $checker = DB::table('users_contact_info')->select('user_id')->where('user_id', auth()->user()->id)->exists();
        if ($checker) {
            DB::table('users_contact_info')
            ->where('user_id', auth()->user()->id)
            ->update(['surname' => $request->surname,
                      'lastname' => $request->lastname,
                      'address' => $request->address,
                      'city' => $request->city,
                      'postcode' => $request->postcode,
                      'country' => $request->country,
                      'phone' => $request->phone]);
            return response()->json('data boli uspesne aktualizovane');
        } else {
            DB::table('users_contact_info')->insert([
                      'user_id' => auth()->user()->id,
                      'surname' => $request->surname,
                      'lastname' => $request->lastname,
                      'address' => $request->address,
                      'city' => $request->city,
                      'postcode' => $request->postcode,
                      'country' => $request->country,
                      'phone' => $request->phone]);
            return response()->json('data boli uspesne ulozene');
        }
        return response()->json('error');
    }

    public function checkIfContactFormExist()
    {
        $contactFormExist = DB::table('users_contact_info')
          ->where('user_id', '=', auth()->user()->id)
          ->first();

        if ($contactFormExist) {
            return response('1');
        } else {
            return response('0');
        }
    }

    public function getNumberOfFriends() {
      $numberOfFriends = DB::table('friendships')
                           ->where([['requester', '=', auth()->user()->id],['friendship_status', '=', 1]])
                           ->orWhere([['user_requested', '=', auth()->user()->id],['friendship_status', '=', 1]])
                           ->count();
      return response()->json($numberOfFriends);
    }

    public function getContactForm()
    {
        $getData = DB::table('users_contact_info')->where('user_id', auth()->user()->id)->get();
        return response()->json($getData);
    }

    public function getapf(Request $request) {
      $data = $request->text;
      if ($data == '') {
        $test = array();
        return response($test);
      } else {
        $array1 = DB::table('friendships')
                    ->join('users','users.id','=','friendships.user_requested')
                    ->select('friendships.user_requested', 'users.name', 'users.email', 'users.status', 'users.avatar')
                    ->where([['requester', '=', auth()->user()->id], ['friendship_status', '=', '1'], ['users.name', 'LIKE', '%'.$data.'%']])
                    ->get();
        $array2 = DB::table('friendships')
                    ->join('users','users.id','=','friendships.requester')
                    ->select('friendships.requester', 'users.name', 'users.email', 'users.status', 'users.avatar')
                    ->where([['user_requested', '=', auth()->user()->id], ['friendship_status', '=', '1'], ['users.name', 'LIKE', '%'.$data.'%']])
                    ->get();

        $merged = $array2->merge($array1);
        $result = $merged->all();
        return response($result);
      }
    }
}
