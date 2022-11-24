<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Events\NewMessage;
use App\Events\MessageReactionData;
use App\Events\MessageStatusRead;
// use App\Events\UnreadMessages;

use App\Models\Friendship;
use Illuminate\Support\Facades\DB;

class MessengerController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
      $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
        ->where('to', auth()->user()->id)
        ->where('read', false)
        ->groupBy('from')
        ->get();

     $user_id = auth()->user()->id;

     // $contacts2 = Message::with('fromContact', 'toContact')
     //                     ->where(function ($q) use ($user_id) {
     //                         $q->where('to', $user_id);
     //                         $q->groupBy('from');
     //                         $q->orderBy('created_at', 'DESC');
     //                         // $q->limit(1);
     //                     })->orWhere(function ($q) use ($user_id) {
     //                         $q->where('from', $user_id);
     //                         $q->groupBy('to');
     //                         $q->orderBy('created_at', 'DESC');
     //                         // $q->limit(1);
     //                     })
     //                     ->groupBy('from')
     //                     ->orderBy('created_at', 'DESC')
     //                     ->get();
     $contacts = DB::select("select messages.*, users.id as user_id, users.name, users.email, users.status, users.avatar, users.created_at as user_created_at
      FROM messages
      INNER JOIN users ON users.id = messages.from
      WHERE messages.created_at in (Select max(messages.created_at) FROM messages GROUP BY `from`)
      AND messages.to = $user_id
      ORDER BY messages.created_at DESC
      ");
      $collection = collect($contacts);
      $collection = $collection->map(function ($contact) use ($unreadIds) {
          $contactUnread = $unreadIds->where('sender_id', $contact->user_id)->first();
          $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;
          return $contact;
      });

      return response()->json($contacts);
  }

  public function getAllUnreadMessages()
  {
      $allUnreadMessages = DB::table('messages')->where([['to', '=', auth()->user()->id],['read', '=', false]])->count();
      // broadcast(new UnreadMessages($allUnreadMessages))->toOthers();
      return response()->json($allUnreadMessages);
  }

  public function getMessagesFor($id)
  {
      // mark all messages with the selected contact as read
      Message::where('from', $id)->where('to', auth()->user()->id)->update(['read' => true]);
      // get all messages between the authenticated user and the selected user
      $messages = Message::with('fromContact',
                                'toContact',
                                'messagereactionmodel.usermessagereactionmodel',
                                'replyMessage', 'replyMessage.fromContact'
                                )->where(function ($q) use ($id) {
                                    $q->where('from', auth()->user()->id);
                                    $q->where('to', $id);
                                })->orWhere(function ($q) use ($id) {
                                    $q->where('from', $id);
                                    $q->where('to', auth()->user()->id);
                                })
                                ->get();
      // $messages = Message::where(function ($q) use ($id) {
      //     $q->where('from', auth()->user()->id);
      //     $q->where('to', $id);
      // })->orWhere(function ($q) use ($id) {
      //     $q->where('from', $id);
      //     $q->where('to', auth()->user()->id);
      // })
      // ->get();
      broadcast(new MessageStatusRead($id))->toOthers();
      return response()->json($messages);
  }

  public function send(Request $request) {
    $data = $request->all();
    $array = array();
    $type;
    $message;
    if ($data['reply_id'] == '0') {
      $data['reply_id'] = NULL;
    }

    if(count($data) == 3){
      if(empty($data['text'])){
        foreach ($request->file('image_video_url') as $index => $file) {
          $last_message = DB::table('messages')->latest('id')->first();

          if ($last_message != null) {
            $last_index = $last_message->id + 1;
          } else {
            $last_index = 1;
          }
          $name = 'message_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);

          if (substr($file->getClientOriginalName(), -3) == 'mp4') {
            $type = "video_url";
          } else {
            $type = "image_url";
          }
          $array[] = array(
            'name' => $name,
            'file' =>  $file,
            'last_message_id' => $last_index,
            'type' => $type
            );
        }
        if ($array[0]["type"] == "video_url") {
          $message = Message::create([
            'from' => auth()->user()->id,
            'to' => $data['contact_id'],
            'text' => NULL,
            'image_url' => NULL,
            'video_url' => $array[0]["name"],
            'reply_id' => $data['reply_id'],
            'status' => 'created',
            'read' => 0,
            'count_reactions' => 0
          ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        } else {
          $message = Message::create([
            'from' => auth()->user()->id,
            'to' => $data['contact_id'],
            'text' => NULL,
            'image_url' => $array[0]["name"],
            'video_url' => NULL,
            'reply_id' => $data['reply_id'],
            'status' => 'created',
            'read' => 0,
            'count_reactions' => 0
          ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        }
      } else {
        //text only
        $message = Message::create([
          'from' => auth()->user()->id,
          'to' => $data['contact_id'],
          'text' => $data['text'],
          'image_url' => NULL,
          'video_url' => NULL,
          'reply_id' => $data['reply_id'],
          'status' => 'created',
          'read' => 0,
          'count_reactions' => 0
        ]);
      }
    } else {
      // both
      foreach ($request->file('image_video_url') as $index => $file) {
        $last_message = DB::table('messages')->latest('id')->first();

        if ($last_message != null) {
          $last_index = $last_message->id + 1;
        } else {
          $last_index = 1;
        }
        $name = 'message_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);

        if (substr($file->getClientOriginalName(), -3) == 'mp4') {
          $type = "video_url";
        } else {
          $type = "image_url";
        }
        $array[] = array(
          'name' => $name,
          'file' =>  $file,
          'last_message_id' => $last_index,
          'type' => $type
          );
      }
      if ($array[0]["type"] == "video_url") {
        $message = Message::create([
          'from' => auth()->user()->id,
          'to' => $data['contact_id'],
          'text' => $data['text'],
          'image_url' => NULL,
          'video_url' => $array[0]["name"],
          'reply_id' => $data['reply_id'],
          'status' => 'created',
          'read' => 0,
          'count_reactions' => 0
        ]);
        Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
      } else {
        $message = Message::create([
          'from' => auth()->user()->id,
          'to' => $data['contact_id'],
          'text' => $data['text'],
          'image_url' => $array[0]["name"],
          'video_url' => NULL,
          'reply_id' => $data['reply_id'],
          'status' => 'created',
          'read' => 0,
          'count_reactions' => 0
        ]);
        Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
      }
    }

      $allUnreadMessages = DB::table('messages')->where([['to', '=', $request->contact_id],['read', '=', false]])->count();
      Message::orderBy('id', 'desc')->first()->update(['totalUnreadMsgTo' => $allUnreadMessages]);

      // broadcast(new NewMessage($message->load('fromContact')->load('toContact')->load('messagereactionmodel.usermessagereactionmodel')->load('replyMessage', 'replyMessage.fromContact')))->toOthers();
      broadcast(new NewMessage($message->load('fromContact', 'toContact', 'messagereactionmodel.usermessagereactionmodel', 'replyMessage', 'replyMessage.fromContact')))->toOthers();

      return response()->json($message);
  }

  public function getUserById($id) {
        $getUserById = DB::select("select messages.*, users.name, users.email, users.status, users.avatar
         FROM messages
         INNER JOIN users ON users.id = messages.from
         WHERE messages.created_at in (Select max(messages.created_at) FROM messages GROUP BY `from`)
         AND messages.from = $id
         ORDER BY messages.created_at DESC
         LIMIT 1");
        // DB::table('users')
        //                ->where("id", $id
        //                ->select('users.name', 'users.email', 'users.status', 'users.avatar')
        //                ->get();
        return $getUserById;
    }

    public function getFewNewestMessages() {
      $user_id = auth()->user()->id;
      $fewNewestMessages = DB::select("select messages.*, users.name, users.email, users.status, users.avatar
       FROM messages
       INNER JOIN users ON users.id = messages.from
       WHERE messages.created_at in (Select max(messages.created_at) FROM messages GROUP BY `from`)
       AND messages.to = $user_id
       ORDER BY messages.created_at DESC
       LIMIT 3");

       // AND messages.read = false
      return response()->json($fewNewestMessages);
    }

    //message reactions--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function add_message_reaction(Request $request) {
      $reactionType = $request->reaction_type;
      $message_id = $request->message_id;

      $checkIfExist = MessageReaction::where([['message_id', '=', $message_id],['user_id', '=', auth()->user()->id]])->first();

      //reaction from this user does not exist
      if ($checkIfExist === null) {
        MessageReaction::create([
                            'message_id' => $message_id,
                            'user_id' => auth()->user()->id,
                            'reaction_type' => $reactionType,
                         ]);
        $reactions = DB::table('messages')
                     ->where('id', '=', $message_id)
                     ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                     ->get();

        $newNumber = DB::table('messages')
                     ->where('id', '=', $message_id)
                     ->update([$reactionType => $reactions[0]->$reactionType + 1,
                               'count_reactions' => $reactions[0]->count_reactions + 1]);
      } else {
        // reation from this user already exist
        $previousReactionType = MessageReaction::select('reaction_type')->where([['message_id', '=', $message_id],['user_id', '=', auth()->user()->id]])->get();
        $previousReactionType0 = $previousReactionType[0]->reaction_type;
        MessageReaction::where([['message_id', '=', $message_id],['user_id', '=', auth()->user()->id]])->update(['reaction_type' => $reactionType]);

        $reactions = DB::table('messages')
                     ->where('id', '=', $message_id)
                     ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                     ->get();
        // return response()->json([$reactionType, $previousReactionType[0]->reaction_type]);
        $newNumber = DB::table('messages')
                     ->where('id', '=',$message_id)
                     ->update([$reactionType => $reactions[0]->$reactionType + 1, $previousReactionType0 => $reactions[0]->$previousReactionType0 - 1]);
      }

       $data = array(
         'id' => $message_id,
         'type' => 'addUpdateMessageReaction'
       );

       broadcast(new MessageReactionData($data))->toOthers();

      return response()->json("Success add/updated reaction message");
    }

    public function delete_message_reaction(Request $request) {
      $removeMessageReaction = MessageReaction::where([['message_id', '=', $request->message_id], ['user_id', '=', auth()->user()->id]])->get();
      MessageReaction::where([['message_id', '=', $request->message_id], ['user_id', '=', auth()->user()->id]])->delete();
      $message = Message::where('id', '=', $request->message_id)->get();
      $heart = $removeMessageReaction[0]->reaction_type;
      $message[0]->count_reactions = $message[0]->count_reactions - 1;
      $message[0]->$heart = $message[0]->$heart - 1;
      $message[0]->save();

      $data = array(
        'id' => $request->message_id,
        'type' => 'deleteMessageReaction'
      );

      broadcast(new MessageReactionData($data))->toOthers();

      return response()->json("Success deleted reaction message");
    }
}
