<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserAvatarController;
use App\Http\Controllers\ValidateRegisterLogin;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FriendshipsController;
use App\Http\Controllers\PostsController;
use App\Models\User;

// use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['prefix' => 'internal', 'middleware' => ['auth:api']], function () {
//     Route::get('sockets/serve', function () {
//         \Illuminate\Support\Facades\Artisan::call('websockets:serve');
//     });
// });
//
Route::post('/broadcast', function (Request $request) {
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        array(
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => false,
        'host' => '127.0.0.1',
        'port' => 6001,
        'scheme' => 'http',
        )
    );
    return $pusher->presence_auth($request->request->get('channel_name'), $request->request->get('socket_id'), $request->contact_id);
    // return $pusher->presence_auth($request->request->get('channel_name'), $request->request->get('socket_id'), $request->contact_id, $request->text);
    // return $pusher->socket_auth($request->request->get('channel_name'), $request->request->get('socket_id'));
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/auth/user', [AuthController::class, 'me']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
// Send reset password mail
Route::post('/auth/reset-password', [AuthController::class, 'sendPasswordResetLink']);
// handle reset password form process
Route::post('/auth/reset/password', [AuthController::class, 'callResetPassword']);
// handle activate account form process
Route::post('/auth/verification/account', [AuthController::class, 'callVerificationAccount']);

Route::post('/checkIfUsernameExist', [ValidateRegisterLogin::class, 'checkUsername']);
Route::post('/checkIfEMailExist', [ValidateRegisterLogin::class, 'checkEmail']);

//Messenger

Route::get('/contacts', [MessengerController::class, 'index']);
Route::get('/conversation/{id}', [MessengerController::class, 'getMessagesFor']);
Route::post('/conversation/send', [MessengerController::class, 'send']);
Route::get('/getAllUnreadMessages', [MessengerController::class, 'getAllUnreadMessages']);
Route::get('/getFewNewestMessages', [MessengerController::class, 'getFewNewestMessages']);
//message reaction add/update and delete
Route::post('/add_message_reaction', [MessengerController::class, 'add_message_reaction']);
Route::post('/delete_message_reaction', [MessengerController::class, 'delete_message_reaction']);

//get user by id
Route::get('/user/{id}', [MessengerController::class, 'getUserById']);
//get user by id 2
Route::get('/user_info/{id}', [AuthController::class, 'getUserInfoById']);
//show Profile
Route::get('/profile/{user_id}', [MessengerController::class, 'getUserProfile']);
//get all own friends
Route::get('/friends', [MessengerController::class, 'friends']);

//notifications
Route::post('/sendNotification', [NotificationsController::class, 'sendNotification']);
Route::post('/deleteNotification', [NotificationsController::class, 'deleteNotification']);
Route::post('/addToSaved', [NotificationsController::class, 'addToSaved']);
Route::post('/addToAll', [NotificationsController::class, 'addToAll']);
Route::post('/markAsRead', [NotificationsController::class, 'markAsRead']);
Route::get('/getNotification/{id}', [NotificationsController::class, 'getNotification']);
Route::get('/getNotificationAll/{id}', [NotificationsController::class, 'getNotificationAll']);
Route::get('/getNotificationNew/{id}', [NotificationsController::class, 'getNotificationNew']);
Route::get('/getNotificationSaved/{id}', [NotificationsController::class, 'getNotificationSaved']);
Route::get('/getFewNewestNotifications', [NotificationsController::class, 'getFewNewestNotifications']);
Route::get('/getFewOldNotifications', [NotificationsController::class, 'getFewOldNotifications']);

//Friendships
Route::get('/checkContactForm', [FriendshipsController::class, 'checkIfContactFormExist']);
Route::get('/friendships', [FriendshipsController::class, 'getFriendships']);
Route::get('/getAllPossibleFriends', [FriendshipsController::class, 'getAllPossibleFriends']);
Route::get('/getAllFriendshipRequests', [FriendshipsController::class, 'getAllFriendshipRequests']);
Route::get('/getCountMyFriendshipRequests', [FriendshipsController::class, 'getCountMyFriendshipRequests']);
Route::post('/friendships/sendFriendshipRequest', [FriendshipsController::class, 'sendFriendshipRequest']);
Route::post('/friendships/acceptFriendshipRequest', [FriendshipsController::class, 'acceptFriendshipRequest']);
Route::post('/friendships/refuseFriendshipRequest', [FriendshipsController::class, 'refuseFriendshipRequest']);
Route::post('/friendships/removeFromFriendshipList', [FriendshipsController::class, 'removeFromFriendshipList']);
Route::get('/getNumberOfFriends', [FriendshipsController::class, 'getNumberOfFriends']);
//Friendships profile
Route::get('/checkIfUserIsMyFriend/{id}', [FriendshipsController::class, 'checkIfUserIsMyFriend']);
Route::get('/friendshipsProfile/{id}', [FriendshipsController::class, 'getFriendshipsProfile']);

//upload avatar
Route::post('/upload_avatar', [UserAvatarController::class, 'upload_user_photo']);
Route::post('/delete_avatar', [UserAvatarController::class, 'delete_user_photo']);

//posts add, update, delete, report, get
Route::post('/add_post', [PostsController::class, 'add_post']);
// test img send
Route::post('/add_post_test', [PostsController::class, 'add_post_test']);
//
Route::post('/update_post', [PostsController::class, 'update_post']);
Route::post('/delete_post', [PostsController::class, 'delete_post']);
Route::post('/report_post', [PostsController::class, 'report_post']);

Route::get('/getAllDataAboutPosts/{page}/{perPage}', [PostsController::class, 'getAllDataAboutPosts']);
Route::get('/getAllDataAboutUserPosts/{id}/{page}/{perPage}', [PostsController::class, 'getAllDataAboutUserPosts']);
Route::get('/get_number_of_user_posts', [PostsController::class, 'get_number_of_user_posts']);

//posts_reactions add/update, delete, get
Route::post('/add_post_reaction', [PostsController::class, 'add_post_reaction']);
Route::post('/delete_post_reaction', [PostsController::class, 'delete_post_reaction']);

//posts_shares add/update, delete, get
Route::post('/add_post_share', [PostsController::class, 'add_post_share']);
Route::post('/delete_post_share', [PostsController::class, 'delete_post_share']);

//posts_comments add, update, delete, get
Route::post('/add_post_comment', [PostsController::class, 'add_post_comment']);
//answers_on_comments add, update, delete, get
Route::post('/add_answer_on_comment', [PostsController::class, 'add_answer_on_comment']);
//update, delete, report post comments and answer on post comments
Route::post('/update_post_comment', [PostsController::class, 'update_post_comment']);
Route::post('/delete_post_comment', [PostsController::class, 'delete_post_comment']);
Route::post('/report_post_comment', [PostsController::class, 'report_post_comment']);

//answers_reactions add/update, delete, get
Route::post('/add_comment_reaction', [PostsController::class, 'add_comment_reaction']);
Route::post('/delete_comment_reaction', [PostsController::class, 'delete_comment_reaction']);

//social auth
Route::post('/sociallogin/{provider}', [AuthController::class, 'SocialSignup']);
Route::get('/auth/{provider}/callback', [OAuthController::class, 'handleProviderCallback'])->where('provider', '.*');


Route::post('/getapf', [FriendshipsController::class, 'getapf']);

Route::post('/testik', [PostsController::class, 'testik']);
