<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use FFMpeg;
use File;
use App\Models\User;
use App\Models\Post;
use App\Models\PostReaction;
use App\Models\PostShare;
use App\Models\PostComment;
use App\Models\AnswerOnComment;
use App\Models\CommentReaction;
use App\Events\PostsData;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{

    public function add_post_test(Request $request) {
      $data = $request->all();
      $text = $data['text'];
      $array = array();
      $type;
      foreach ($request->file('image_video_url') as $index => $file) {
        $last_post = DB::table('posts')->latest('id')->first();
        $last_index = $last_post->id + 1;
        $name = substr($file->getClientOriginalName(), 0, -4).'_post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
        if (substr($file->getClientOriginalName(), -3) == 'mp4') {
          $type = "video_url";
        } else {
          $type = "image_url";
        }
        $array[] = array(
          'name' => substr($file->getClientOriginalName(), 0, -4).'_post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4),
          'file' =>  $file,
          'last_post_id' => $last_index,
          'type' => $type
          );
        // Storage::disk('posts_imgs_videos')->put($name, file_get_contents($file));
      }
      return response()->json($array);
    }



    public function add_post(Request $request) {
      $data = $request->all();
      $array = array();
      $type;
      $posts;
      if(count($data) == 1){
        if(empty($data['text'])){
          //video/image only
          foreach ($request->file('image_video_url') as $index => $file) {
            $last_post = DB::table('posts')->latest('id')->first();

            if ($last_post != null) {
              $last_index = $last_post->id + 1;
            } else {
              $last_index = 1;
            }

            // $name = substr($file->getClientOriginalName(), 0, -4).'_post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            $name = 'post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }
            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'last_post_id' => $last_index,
              'type' => $type
              );
          }
          if ($array[0]["type"] == "video_url") {
            $posts = Post::create([
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => NULL,
                        'video_url' => $array[0]["name"],
                        'status' => 'created'
                    ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
            // FFMpeg::fromDisk('posts_imgs_videos')
            //       ->open($array[0]["name"] + '.mp4')
            //       ->getFrameFromSeconds(1);


                  // ->export()
                  // ->toDisk('poster')
                  // ->save($array[0]["name"] + '_poster.png');
          } else {
            $posts = Post::create([
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => $array[0]["name"],
                        'video_url' => NULL,
                        'status' => 'created'
                    ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          }
        } else {
          //text only
          $posts = Post::create([
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => NULL,
                      'video_url' => NULL,
                      'status' => 'created'
                  ]);
        }
      } else {
        //both
        foreach ($request->file('image_video_url') as $index => $file) {
          $last_post = DB::table('posts')->latest('id')->first();

          if ($last_post != null) {
            $last_index = $last_post->id + 1;
          } else {
            $last_index = 1;
          }

          // $name = substr($file->getClientOriginalName(), 0, -4).'_post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          $name = 'post_id_'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          if (substr($file->getClientOriginalName(), -3) == 'mp4') {
            $type = "video_url";
          } else {
            $type = "image_url";
          }
          $array[] = array(
            'name' => $name,
            'file' =>  $file,
            'last_post_id' => $last_index,
            'type' => $type
            );
        }
        if ($array[0]["type"] == "video_url") {
          $posts = Post::create([
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => NULL,
                      'video_url' => $array[0]["name"],
                      'status' => 'created'
                  ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        } else {
          $posts = Post::create([
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => $array[0]["name"],
                      'video_url' => NULL,
                      'status' => 'created'
                  ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        }
      }

      $data = array(
        'id' => $posts->id,
        'type' => 'addNewPost'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json("success added new post");
    }

    public function update_post(Request $request) {
      $data = $request->all();
      $array = array();
      $type;

      if(count($data) == 2){
        if(empty($data['text'])){
          //video/image only
          foreach ($request->file('image_video_url') as $index => $file) {
            $name = 'post_id_'.$data['post_id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);

            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }

            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'type' => $type
            );
          }
          if ($array[0]["type"] == "video_url") {
            $post = Post::where('id', '=', $data['post_id'])->get();
            if ($post[0]->image_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($post[0]->image_url);
            }
            Post::where('id', '=', $data['post_id'])
            ->update([
                'user_id' => auth()->user()->id,
                'text' => NULL,
                'image_url' => NULL,
                'video_url' => $array[0]["name"],
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
            $post = Post::where('id', '=', $data['post_id'])->get();
            if ($post[0]->video_url != null) {
                // delete existing video file
                Storage::disk('posts_imgs_videos')->delete($post[0]->video_url);
            }
            Post::where('id', '=', $data['post_id'])
            ->update([
                'user_id' => auth()->user()->id,
                'text' => NULL,
                'image_url' => $array[0]["name"],
                'video_url' => NULL,
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          }
        } else {
          $post = Post::where('id', '=', $data['post_id'])->get();
          if ($post[0]->video_url != null) {
              // delete existing video file
              Storage::disk('posts_imgs_videos')->delete($post[0]->video_url);
          } else if($post[0]->image_url != null) {
              // delete existing image file
              Storage::disk('posts_imgs_videos')->delete($post[0]->image_url);
          }
          //text only
          Post::where('id', '=', $data['post_id'])
          ->update([
              'user_id' => auth()->user()->id,
              'text' => $data['text'],
              'image_url' => NULL,
              'video_url' => NULL,
              'status' => 'updated'
          ]);
        }
      } else {
        if ($data['image_video_url'][0] != 'same') {
          //if same img/video
          //both
          foreach ($request->file('image_video_url') as $index => $file) {
            $name = 'post_id_'.$data['post_id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);

            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }

            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'type' => $type
              );
          }
          if ($array[0]["type"] == "video_url") {
            $post = Post::where('id', '=', $data['post_id'])->get();
            if ($post[0]->image_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($post[0]->image_url);
            }
            Post::where('id', '=', $data['post_id'])
            ->update([
                'text' => $data['text'],
                'image_url' => NULL,
                'video_url' => $array[0]["name"],
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
            $post = Post::where('id', '=', $data['post_id'])->get();
            if ($post[0]->video_url != null) {
                // delete existing video file
                Storage::disk('posts_imgs_videos')->delete($post[0]->video_url);
            }
            Post::where('id', '=', $data['post_id'])
            ->update([
                'text' => $data['text'],
                'image_url' => $array[0]["name"],
                'video_url' => NULL,
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          }
        } else {
          // if file have tag 'same', it means that there is a same file
          Post::where('id', '=', $data['post_id'])
          ->update([
              'text' => $data['text'],
              'status' => 'updated'
          ]);
        }
      }

      $data = array(
        'id' => $data['post_id'],
        'type' => 'updatedPost'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json("success updated post");
    }

    public function delete_post(Request $request) {
      Post::where('id', '=', $request->id)
          ->update(['status' => 'deleted']);

      $data = array(
        'id' => $request->id,
        'type' => 'deletedPost'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json('post removed');
    }

    public function report_post(Request $request) {
      return response()->json('post updated');
    }

    //post reactions--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function add_post_reaction(Request $request) {
      $reactionType = $request->reaction_type;
      $post_id = $request->post_id;

      $checkIfExist = PostReaction::where([['post_id', '=', $request->post_id],['user_id', '=', auth()->user()->id]])->first();

      //reaction from this user does not exist
      if ($checkIfExist === null) {
        PostReaction::create([
                            'post_id' => $request->post_id,
                            'user_id' => auth()->user()->id,
                            'reaction_type' => $reactionType,
                         ]);
        $reactions = DB::table('posts')
                     ->where('id', '=', $post_id)
                     ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                     ->get();

        $newNumber = DB::table('posts')
                     ->where('id', '=', $post_id)
                     ->update([$reactionType => $reactions[0]->$reactionType + 1,
                               'count_reactions' => $reactions[0]->count_reactions + 1]);
      } else {
        //reation from this user already exist
        $previousReactionType = PostReaction::select('reaction_type')->where([['post_id', '=', $request->post_id],['user_id', '=', auth()->user()->id]])->get();
        $previousReactionType0 = $previousReactionType[0]->reaction_type;
        PostReaction::where([['post_id', '=', $request->post_id],['user_id', '=', auth()->user()->id]])->update(['reaction_type' => $reactionType]);

        $reactions = DB::table('posts')
                     ->where('id', '=', $post_id)
                     ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                     ->get();
        // return response()->json([$reactionType, $previousReactionType[0]->reaction_type]);
        $newNumber = DB::table('posts')
                     ->where('id', '=', $post_id)
                     ->update([$reactionType => $reactions[0]->$reactionType + 1, $previousReactionType0 => $reactions[0]->$previousReactionType0 - 1]);
      }

       $data = array(
         'id' => $post_id,
         'type' => 'addUpdatePostReaction'
       );

       broadcast(new PostsData($data))->toOthers();

      return response()->json($newNumber);
    }

    public function delete_post_reaction(Request $request) {
      $removePostReaction = PostReaction::where([['post_id', '=', $request->post_id], ['user_id', '=', auth()->user()->id]])->get();
      PostReaction::where([['post_id', '=', $request->post_id], ['user_id', '=', auth()->user()->id]])->delete();
      $post = Post::where('id', '=', $request->post_id)->get();
      $heart = $removePostReaction[0]->reaction_type;
      $post[0]->count_reactions = $post[0]->count_reactions - 1;
      $post[0]->$heart = $post[0]->$heart - 1;
      $post[0]->save();

      $data = array(
        'id' => $request->post_id,
        'type' => 'deletePostReaction'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json("Success deleted reaction and updated post");
    }

    //post shares--------------------------------------------------------------------------------------------------------------------------------------------------
    public function add_post_share(Request $request) {
      $post_share = PostShare::create([
                      'post_id' => $request->post_id,
                      'user_id' => auth()->user()->id,
                    ]);
      $reactions = DB::table('posts')
                   ->where('id', '=', $request->post_id)
                   ->select('count_shares')
                   ->get();

      $newNumber = DB::table('posts')
                   ->where('id', '=', $request->post_id)
                   ->update(['count_shares' => $reactions[0]->count_shares + 1]);

      $data = array(
       'id' => $request->post_id,
       'type' => 'addPostShare'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json($newNumber);
    }

    public function delete_post_share(Request $request) {
      PostShare::where([['post_id', '=', $request->post_id], ['user_id', '=', auth()->user()->id]])->delete();
      $post = Post::where('id', '=', $request->post_id)->get();
      $post[0]->count_shares = $post[0]->count_shares - 1;
      $post[0]->save();

      $data = array(
       'id' => $request->post_id,
       'type' => 'deletePostShare'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json('post reaction delete');
    }

    //add post comments----------add answer on comments----------update both------delete both------report both-----------------------------------------------------------------------------------------------
    public function add_post_comment(Request $request) {
      $data = $request->all();
      $array = array();
      $type;
      $post_comment;

      if ($data['chip'] == '0') {
        $data['chip'] = NULL;
      }

      if(count($data) == 3){
        if(empty($data['text'])){
          foreach ($request->file('image_video_url') as $index => $file) {
            $last_post_comment = DB::table('posts_comments')->latest('id')->first();

            if ($last_post_comment != null) {
              $last_index = $last_post_comment->id + 1;
            } else {
              $last_index = 1;
            }

            $name = 'post_comment'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }
            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'post_comment_id' => $last_index,
              'type' => $type
              );
          }
          if ($array[0]["type"] == "video_url") {
            $post_comment = PostComment::create([
                        'post_id' => $data['post_id'],
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => NULL,
                        'video_url' => $array[0]["name"],
                        'status' => 'created',
                        'chip' => $data['chip']
                      ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
            $post_comment = PostComment::create([
                        'post_id' => $data['post_id'],
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => $array[0]["name"],
                        'video_url' => NULL,
                        'status' => 'created',
                        'chip' => $data['chip']
                      ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          }
        //
        }
        //
        $post_comment = PostComment::create([
                    'post_id' => $data['post_id'],
                    'user_id' => auth()->user()->id,
                    'text' => $data['text'],
                    'image_url' => NULL,
                    'video_url' => NULL,
                    'status' => 'created',
                    'chip' => $data['chip']
                  ]);
      } else {
        foreach ($request->file('image_video_url') as $index => $file) {
          $last_post_comment = DB::table('posts_comments')->latest('id')->first();

          if ($last_post_comment != null) {
            $last_index = $last_post_comment->id + 1;
          } else {
            $last_index = 1;
          }

          $name = 'post_comment'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          if (substr($file->getClientOriginalName(), -3) == 'mp4') {
            $type = "video_url";
          } else {
            $type = "image_url";
          }
          $array[] = array(
            'name' => $name,
            'file' =>  $file,
            'post_comment_id' => $last_index,
            'type' => $type
            );
        }
        if ($array[0]["type"] == "video_url") {
          $post_comment = PostComment::create([
                      'post_id' => $data['post_id'],
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => NULL,
                      'video_url' => $array[0]["name"],
                      'status' => 'created',
                      'chip' => $data['chip']
                    ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        } else {
          $post_comment = PostComment::create([
                      'post_id' => $data['post_id'],
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => $array[0]["name"],
                      'video_url' => NULL,
                      'status' => 'created',
                      'chip' => $data['chip']
                    ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        }
      }

      $postsData = DB::table('posts')
                   ->where('id', '=', $request->post_id)
                   ->select('count_comments')
                   ->get();

      $newNumber = DB::table('posts')
                   ->where('id', '=', $request->post_id)
                   ->update(['count_comments' => $postsData[0]->count_comments + 1]);

      $data = array(
        'id' => $request->post_id,
        'post_comment_id' => $post_comment->id,
        'type' => 'addPostComment'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json($newNumber);
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------------
    public function add_answer_on_comment(Request $request) {
      $data = $request->all();
      $array = array();
      $type;

      if ($data['chip'] == '0') {
        $data['chip'] = NULL;
      }

      if(count($data) == 3){
        if(empty($data['text'])){
          foreach ($request->file('image_video_url') as $index => $file) {
            $last_answer_on_comment = DB::table('answers_on_comments')->latest('id')->first();

            if ($last_answer_on_comment != null) {
              $last_index = $last_answer_on_comment->id + 1;
            } else {
              $last_index = 1;
            }

            $name = 'answer_on_comment'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }
            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'answer_on_comment_id' => $last_index,
              'type' => $type
              );
          }
          if ($array[0]["type"] == "video_url") {
            $answer_on_comment = AnswerOnComment::create([
                        'comment_id' => $data['comment_id'],
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => NULL,
                        'video_url' => $array[0]["name"],
                        'status' => 'created',
                        'chip' => $data['chip']
                      ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
            $answer_on_comment = AnswerOnComment::create([
                        'comment_id' => $data['comment_id'],
                        'user_id' => auth()->user()->id,
                        'text' => NULL,
                        'image_url' => $array[0]["name"],
                        'video_url' => NULL,
                        'status' => 'created',
                        'chip' => $data['chip']
                      ]);
            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          }
        //
        }
        //
        AnswerOnComment::create([
                    'comment_id' => $data['comment_id'],
                    'user_id' => auth()->user()->id,
                    'text' => $data['text'],
                    'image_url' => NULL,
                    'video_url' => NULL,
                    'status' => 'created',
                    'chip' => $data['chip']
                  ]);
      } else {
        foreach ($request->file('image_video_url') as $index => $file) {
          $last_answer_on_comment = DB::table('answers_on_comments')->latest('id')->first();

          if ($last_answer_on_comment != null) {
            $last_index = $last_answer_on_comment->id + 1;
          } else {
            $last_index = 1;
          }

          $name = 'answer_on_comment'.$last_index.'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          if (substr($file->getClientOriginalName(), -3) == 'mp4') {
            $type = "video_url";
          } else {
            $type = "image_url";
          }
          $array[] = array(
            'name' => $name,
            'file' =>  $file,
            'answer_on_comment_id' => $last_index,
            'type' => $type
            );
        }
        if ($array[0]["type"] == "video_url") {
          $answer_on_comment = AnswerOnComment::create([
                      'comment_id' => $data['comment_id'],
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => NULL,
                      'video_url' => $array[0]["name"],
                      'status' => 'created',
                      'chip' => $data['chip']
                    ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        } else {
          $answer_on_comment = AnswerOnComment::create([
                      'comment_id' => $data['comment_id'],
                      'user_id' => auth()->user()->id,
                      'text' => $data['text'],
                      'image_url' => $array[0]["name"],
                      'video_url' => NULL,
                      'status' => 'created',
                      'chip' => $data['chip']
                    ]);
          Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
        }
      }

      $commentsData = DB::table('posts_comments')
                  ->where('id', '=', $request->comment_id)
                  ->select('post_id', 'count_comments')
                  ->get();
      $postsData = DB::table('posts')
                   ->where('id', '=', $commentsData[0]->post_id)
                   ->select('count_comments')
                   ->get();

      $newNumber = DB::table('posts')
                   ->where('id', '=', $commentsData[0]->post_id)
                   ->update(['count_comments' => $postsData[0]->count_comments + 1]);
      $newNumberComments = DB::table('posts_comments')
                    ->where('id', '=', $request->comment_id)
                    ->update(['count_comments' => $commentsData[0]->count_comments + 1]);

      $postId = DB::table('posts_comments')
                    ->select('posts_comments.post_id')
                    ->where('id', '=', $request->comment_id)
                    ->get();

      $data = array(
        'id' => $postId[0]->post_id,
        'post_comment_id' => $request->comment_id,
        'type' => 'addAnswerOnComment'
      );

      broadcast(new PostsData($data))->toOthers();

      return response()->json($request);
    }

    public function testik(Request $request) {
      $data = Post::where('id', '=', $request->id)->get();

      if ($data[0]->video_url != null) {
        $filesToReturn = [];
        $files = File::files(storage_path() . "/app/public/post_img_video");
        foreach ($files as $file) {
            $fileName = basename($file);
            $file = Storage::url('post_img_video/' . $fileName);
            array_push($filesToReturn, url($file));
        }
        return response()->json($filesToReturn);
      } else {
        $file = asset('storage/post_img_video/'. $data[0]->image_url);
        $file2 = File::get($file);
        // $files = File::files(storage_path() . "/app/public/post_img_video");
        // foreach ($files as $file) {
        //   $fileName = File::name($file);
        //   if ($fileName.'.png' === $data[0]->image_url) {
        //     return response()->json($file);
        //   }
        // }
        return response()->json($file2);
      }
    }
    //--------------------------------------------------------------------------------------------------------------------------------------------------
    public function update_post_comment(Request $request) {
      $data = $request->all();
      $type;
      if(count($data) == 3){
        //if there is text or img/video
        if(empty($data['text'])){
          //if there is no text => img/video
          foreach ($request->file('image_video_url') as $index => $file) {
            $name;
            if ($data['type'] == 'answers_on_comments') {
              $name = 'answer_on_comment_id'.$data['id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            } else {
              $name = 'post_comment_id'.$data['id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
            }

            if (substr($file->getClientOriginalName(), -3) == 'mp4') {
              $type = "video_url";
            } else {
              $type = "image_url";
            }

            $array[] = array(
              'name' => $name,
              'file' =>  $file,
              'type' => $type
            );
          }
          //
          if ($array[0]["type"] == "video_url") {
            if ($data['type'] == 'answers_on_comments') {
              //if it is answer on comment type
              $postComment = AnswerOnComment::where('id', '=', $data['id'])->get();
              if ($postComment[0]->image_url != null) {
                  // delete existing image file
                  Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
              }
              AnswerOnComment::where('id', '=', $data['id'])
              ->update([
                  'text' => NULL,
                  'image_url' => NULL,
                  'video_url' => $array[0]["name"],
                  'status' => 'updated'
              ]);

              Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
            } else {
             //else if it is post comment type
             $postComment = PostComment::where('id', '=', $data['id'])->get();
             if ($postComment[0]->image_url != null) {
                 // delete existing image file
                 Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
             }
             PostComment::where('id', '=', $data['id'])
             ->update([
                 'text' => NULL,
                 'image_url' => NULL,
                 'video_url' => $array[0]["name"],
                 'status' => 'updated'
             ]);

             Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
           }
          //
          } else {
              if ($data['type'] == 'answers_on_comments') {
                //if it is answer on comment type
                $postComment = AnswerOnComment::where('id', '=', $data['id'])->get();
                if ($postComment[0]->video_url != null) {
                    // delete existing image file
                    Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
                }
                AnswerOnComment::where('id', '=', $data['id'])
                ->update([
                    'text' => NULL,
                    'image_url' => $array[0]["name"],
                    'video_url' => NULL,
                    'status' => 'updated'
                ]);

                Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
              } else {
               //else if it is post comment type
               $postComment = PostComment::where('id', '=', $data['id'])->get();
               if ($postComment[0]->video_url != null) {
                   // delete existing image file
                   Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
               }
               PostComment::where('id', '=', $data['id'])
               ->update([
                   'text' => NULL,
                   'image_url' => $array[0]["name"],
                   'video_url' => NULL,
                   'status' => 'updated'
               ]);

               Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
             }
          }
          //
        } else {
          //else there is text
          if ($data['type'] == 'answers_on_comments') {
            //if it is answer on comment type
            $postComment = AnswerOnComment::where('id', '=', $data['id'])->get();
            if ($postComment[0]->video_url != null) {
                // delete existing video file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
            } else if($postComment[0]->image_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
            }

            AnswerOnComment::where('id', '=', $data['id'])
            ->update([
                'text' => $data['text'],
                'image_url' => NULL,
                'video_url' => NULL,
                'status' => 'updated'
            ]);
          } else {
            //else if it is post comment type
            $postComment = PostComment::where('id', '=', $data['id'])->get();
            if ($postComment[0]->video_url != null) {
                // delete existing video file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
            } else if($postComment[0]->image_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
            }

            PostComment::where('id', '=', $data['id'])
            ->update([
                'text' => $data['text'],
                'image_url' => NULL,
                'video_url' => NULL,
                'status' => 'updated'
            ]);
          }
        }
        //
      } else {
        // else there is text and img/video
        foreach ($request->file('image_video_url') as $index => $file) {
          $name;
          if ($data['type'] == 'answers_on_comments') {
            $name = 'answer_on_comment_id'.$data['id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          } else {
            $name = 'post_comment_id'.$data['id'].'_user_id_'.auth()->user()->id.'_id_'.$index.substr($file->getClientOriginalName(), -4);
          }

          if (substr($file->getClientOriginalName(), -3) == 'mp4') {
            $type = "video_url";
          } else {
            $type = "image_url";
          }

          $array[] = array(
            'name' => $name,
            'file' =>  $file,
            'type' => $type
          );
        }
        //
        if ($array[0]["type"] == "video_url") {
          if ($data['type'] == 'answers_on_comments') {
            //if it is answer on comment type
            $postComment = AnswerOnComment::where('id', '=', $data['id'])->get();
            if ($postComment[0]->image_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
            }
            AnswerOnComment::where('id', '=', $data['id'])
            ->update([
                'text' => $data['text'],
                'image_url' => NULL,
                'video_url' => $array[0]["name"],
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
           //else if it is post comment type
           $postComment = PostComment::where('id', '=', $data['id'])->get();
           if ($postComment[0]->image_url != null) {
               // delete existing image file
               Storage::disk('posts_imgs_videos')->delete($postComment[0]->image_url);
           }
           PostComment::where('id', '=', $data['id'])
           ->update([
               'text' => $data['text'],
               'image_url' => NULL,
               'video_url' => $array[0]["name"],
               'status' => 'updated'
           ]);

           Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
         }
        //
        } else {
          if ($data['type'] == 'answers_on_comments') {
            //if it is answer on comment type
            $postComment = AnswerOnComment::where('id', '=', $data['id'])->get();
            if ($postComment[0]->video_url != null) {
                // delete existing image file
                Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
            }
            AnswerOnComment::where('id', '=', $data['id'])
            ->update([
                'text' => $data['text'],
                'image_url' => $array[0]["name"],
                'video_url' => NULL,
                'status' => 'updated'
            ]);

            Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
          } else {
           //else if it is post comment type
           $postComment = PostComment::where('id', '=', $data['id'])->get();
           if ($postComment[0]->video_url != null) {
               // delete existing image file
               Storage::disk('posts_imgs_videos')->delete($postComment[0]->video_url);
           }
           PostComment::where('id', '=', $data['id'])
           ->update([
               'text' => $data['text'],
               'image_url' => $array[0]["name"],
               'video_url' => NULL,
               'status' => 'updated'
           ]);

           Storage::disk('posts_imgs_videos')->put($array[0]["name"], file_get_contents($array[0]["file"]));
         }
        }
        //
      }

      if ($data['type'] == "answers_on_comments") {
        $commentId = AnswerOnComment::where('id', '=', $data['id'])->get();
        $postId = PostComment::where('id', '=', $commentId[0]->comment_id)->get();
        $data = array(
          'id' => $postId[0]->post_id,
          'type' => 'updatedComment'
        );

        broadcast(new PostsData($data))->toOthers();
      } else {
        $postId = PostComment::where('id', '=', $data['id'])->get();
        $data = array(
          'id' => $postId[0]->post_id,
          'type' => 'updatedComment'
        );
        broadcast(new PostsData($data))->toOthers();
      }
      return response()->json("success updated comment");
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------------
    public function delete_post_comment(Request $request) {
      if ($request->type == 'answers_on_comments') {
        AnswerOnComment::where('id', '=', $request->id)
            ->update(['status' => 'deleted']);

        //get post id
        $commentId = DB::table('answers_on_comments')
                      ->select('answers_on_comments.comment_id')
                      ->where('id', '=', $request->id)
                      ->get();

        $postComment = PostComment::where('id', '=', $commentId[0]->comment_id)->get();
        $postComment[0]->count_comments = $postComment[0]->count_comments - 1;
        $postComment[0]->save();

        $postId = DB::table('posts_comments')
                     ->select('posts_comments.post_id')
                     ->where('id', '=', $commentId[0]->comment_id)
                     ->get();

        $post = Post::where('id', '=', $postId[0]->post_id)->get();
        $post[0]->count_comments = $post[0]->count_comments - 1;
        $post[0]->save();

        $data = array(
         'id' => $postId[0]->post_id,
         'type' => 'deletePostComment'
        );

       broadcast(new PostsData($data))->toOthers();

        return response()->json('post comment deleted');
      } else {
        PostComment::where('id', '=', $request->id)
            ->update(['status' => 'deleted']);

        $post_comment = PostComment::where('id', '=', $request->id)->get();
        $postId = DB::table('posts_comments')
                     ->select('posts_comments.post_id')
                     ->where('id', '=', $request->id)
                     ->get();

        $post = Post::where('id', '=', $postId[0]->post_id)->get();
        $post[0]->count_comments = $post[0]->count_comments - ($post_comment[0]->count_comments + 1);
        $post[0]->save();

        $data = array(
          'id' => $postId[0]->post_id,
          'type' => 'deletePostComment'
        );

        broadcast(new PostsData($data))->toOthers();

        return response()->json('post comment deleted');
      }
    }

    //--------------------------------------------------------------------------------------------------------------------------------------------------
    public function report_post_comment(Request $request) {
      return response()->json('post comment reported');
    }


    //comment reactions--------------------------------------------------------------------------------------------------------------------------------------------------
    public function add_comment_reaction(Request $request) {
      $reactionType = $request->reaction_type;
      if ($request->type == 'answers_on_comments') {
        //reaction from this user does not exist
        $checkIfExist = CommentReaction::where([['answer_on_comment_id', '=',  $request->id],['user_id', '=', auth()->user()->id]])->first();
        if ($checkIfExist === null) {
          $post_reaction = CommentReaction::create([
                              'answer_on_comment_id' => $request->id,
                              'comment_id' => 0,
                              'user_id' => auth()->user()->id,
                              'reaction_type' => $reactionType,
                           ]);
          $reactions = DB::table('answers_on_comments')
                     ->where('id', '=', $request->id)
                     ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                     ->get();

          $newNumber = DB::table('answers_on_comments')
                     ->where('id', '=', $request->id)
                     ->update([$reactionType => $reactions[0]->$reactionType + 1,
                               'count_reactions' => $reactions[0]->count_reactions + 1]);
        } else {
          //reaction from this user already exist
          $previousReactionType = CommentReaction::select('reaction_type')->where([['answer_on_comment_id', '=', $request->id],['user_id', '=', auth()->user()->id]])->get();
          $previousReactionType0 = $previousReactionType[0]->reaction_type;
          CommentReaction::where([['answer_on_comment_id', '=', $request->id],['user_id', '=', auth()->user()->id]])->update(['reaction_type' => $reactionType]);

          $reactions = DB::table('answers_on_comments')
                       ->where('id', '=', $request->id)
                       ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                       ->get();

          $newNumber = DB::table('answers_on_comments')
                       ->where('id', '=', $request->id)
                       ->update([$reactionType => $reactions[0]->$reactionType + 1, $previousReactionType0 => $reactions[0]->$previousReactionType0 - 1]);
        }
          //get post id
          $commentId = DB::table('answers_on_comments')
                        ->select('answers_on_comments.comment_id')
                        ->where('id', '=', $request->id)
                        ->get();


          $postId = DB::table('posts_comments')
                       ->select('posts_comments.post_id')
                       ->where('id', '=', $commentId[0]->comment_id)
                       ->get();

          $data = array(
           'id' => $postId[0]->post_id,
           'type' => 'addUpdateCommentReaction'
          );

         broadcast(new PostsData($data))->toOthers();

         return response()->json($newNumber);
      } else {
        $checkIfExist = CommentReaction::where([['comment_id', '=',  $request->id],['user_id', '=', auth()->user()->id]])->first();
        if ($checkIfExist === null) {
          $post_reaction = CommentReaction::create([
                              'comment_id' => $request->id,
                              'answer_on_comment_id' => 0,
                              'user_id' => auth()->user()->id,
                              'reaction_type' => $reactionType,
                           ]);
          $reactions = DB::table('posts_comments')
                      ->where('id', '=', $request->id)
                      ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                      ->get();

          $newNumber = DB::table('posts_comments')
                      ->where('id', '=', $request->id)
                      ->update([$reactionType => $reactions[0]->$reactionType + 1,
                                'count_reactions' => $reactions[0]->count_reactions + 1]);
        } else {
          //reaction from this user already exist
          $previousReactionType = CommentReaction::select('reaction_type')->where([['comment_id', '=', $request->id],['user_id', '=', auth()->user()->id]])->get();
          $previousReactionType0 = $previousReactionType[0]->reaction_type;
          CommentReaction::where([['comment_id', '=', $request->id],['user_id', '=', auth()->user()->id]])->update(['reaction_type' => $reactionType]);

          $reactions = DB::table('posts_comments')
                       ->where('id', '=', $request->id)
                       ->select('like', 'heart', 'funny', 'surprise', 'sad', 'angry', 'count_reactions')
                       ->get();

          $newNumber = DB::table('posts_comments')
                       ->where('id', '=', $request->id)
                       ->update([$reactionType => $reactions[0]->$reactionType + 1, $previousReactionType0 => $reactions[0]->$previousReactionType0 - 1]);
        }

        $postId = DB::table('posts_comments')
                      ->select('posts_comments.post_id')
                      ->where('id', '=', $request->id)
                      ->get();

        $data = array(
          'id' => $postId[0]->post_id,
          'type' => 'addUpdateCommentReaction'
        );

        broadcast(new PostsData($data))->toOthers();

        return response()->json($newNumber);
      }
    }

    public function delete_comment_reaction(Request $request) {
      if ($request->type == 'answers_on_comments') {
        $removeCommentReaction = CommentReaction::where([['answer_on_comment_id', '=', $request->id], ['user_id', '=', auth()->user()->id]])->get();
        CommentReaction::where([['answer_on_comment_id', '=', $request->id], ['user_id', '=', auth()->user()->id]])->delete();
        $answerOnComment = AnswerOnComment::where('id', '=', $request->id)->get();
        $heart = $removeCommentReaction[0]->reaction_type;
        $answerOnComment[0]->count_reactions = $answerOnComment[0]->count_reactions - 1;
        $answerOnComment[0]->$heart = $answerOnComment[0]->$heart - 1;
        $answerOnComment[0]->save();

        //get post id
        $commentId = DB::table('answers_on_comments')
                      ->select('answers_on_comments.comment_id')
                      ->where('id', '=', $request->id)
                      ->get();

        $postId = DB::table('posts_comments')
                       ->select('posts_comments.post_id')
                       ->where('id', '=', $commentId[0]->comment_id)
                       ->get();

         $data = array(
           'id' => $postId[0]->post_id,
           'type' => 'deleteCommentReaction'
         );

         broadcast(new PostsData($data))->toOthers();

        return response()->json('answer_on_comment reaction delete');
      } else {
        $removeCommentReaction = CommentReaction::where([['comment_id', '=', $request->id], ['user_id', '=', auth()->user()->id]])->get();
        CommentReaction::where([['comment_id', '=', $request->id], ['user_id', '=', auth()->user()->id]])->delete();
        $postComment = PostComment::where('id', '=', $request->id)->get();
        $heart = $removeCommentReaction[0]->reaction_type;
        $postComment[0]->count_reactions = $postComment[0]->count_reactions - 1;
        $postComment[0]->$heart = $postComment[0]->$heart - 1;
        $postComment[0]->save();

        $postId = DB::table('posts_comments')
                      ->select('posts_comments.post_id')
                      ->where('id', '=', $request->id)
                      ->get();
        $data = array(
          'id' => $postId[0]->post_id,
          'type' => 'deleteCommentReaction'
        );

        broadcast(new PostsData($data))->toOthers();

        return response()->json('post_comment reaction delete');
      }
    }

    public function get_number_of_user_posts() {
      $numberOfUserPosts = DB::table('posts')
                           ->where([['user_id', auth()->user()->id], ['status', '!=', 'deleted']])
                           ->count();
      return response()->json($numberOfUserPosts);
    }

    public function getAllDataAboutPosts($page, $perPage) {
      // $checkIfExist = Post::first();
      // if ($checkIfExist != null) {
        $allDataPost = Post::with('userpostmodel',
                        'postcommentmodel.userpostcommentmodel',
                        'postcommentmodel.userpostcommentmodel2',
                        'postcommentmodel.commentreactionmodel2.usercommentreactionmodel',
                        'postcommentmodel.answeroncommentmodel.useransweroncommentmodel',
                        'postcommentmodel.answeroncommentmodel.useransweroncommentmodel2',
                        'postcommentmodel.answeroncommentmodel.commentreactionmodel.usercommentreactionmodel',
                        'postreactionmodel.userpostreactionmodel',
                        'postsharemodel.userpostsharemodel')
                        ->where('status', '!=', 'deleted')
                        ->orderBy('id', 'desc')
                        ->skip($page)
                        ->take($perPage)
                        ->get();
        $allDataPostCount = Post::where('status', '!=', 'deleted')->count();

        return response()->json([$allDataPost, $allDataPostCount]);
     // } else {
     //   return response()->json([null, 0]);
     // }
    }

    public function getAllDataAboutUserPosts($id, $page, $perPage) {
      $allDataPostUser = Post::with('userpostmodel',
                          'postcommentmodel.userpostcommentmodel',
                          'postcommentmodel.commentreactionmodel2.usercommentreactionmodel',
                          'postcommentmodel.answeroncommentmodel.useransweroncommentmodel',
                          'postcommentmodel.answeroncommentmodel.commentreactionmodel.usercommentreactionmodel',
                          'postreactionmodel.userpostreactionmodel',
                          'postsharemodel.userpostsharemodel')
                          ->where([['posts.user_id', '=', $id], ['status', '!=', 'deleted']])
                          ->orderBy('id', 'desc')
                          ->skip($page)
                          ->take($perPage)
                          ->get();
      // $allDataPost = array();
      //
      // if (count($getAllDataPost) != 0) {
      //   for ($i=$page; $i < $page+$perPage; $i++) {
      //     array_push($allDataPost, $getAllDataPost[$i]);
      //   }
      // }


      $allDataPostCount = Post::where([['posts.user_id', '=', $id], ['status', '!=', 'deleted']])->count();

      return response()->json([$allDataPostUser, $allDataPostCount]);
    }
}
