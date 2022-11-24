<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostsData implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
      // $this->allDataPost = $allDataPost;
      $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('postdata');
        // return new Channel('postdata');
    }

    public function broadcastWith()
    {
      $postData = Post::with('userpostmodel',
                     'postcommentmodel.userpostcommentmodel',
                     'postcommentmodel.userpostcommentmodel2',
                     'postcommentmodel.commentreactionmodel2.usercommentreactionmodel',
                     'postcommentmodel.answeroncommentmodel.useransweroncommentmodel',
                     'postcommentmodel.answeroncommentmodel.useransweroncommentmodel2',
                     'postcommentmodel.answeroncommentmodel.commentreactionmodel.usercommentreactionmodel',
                     'postreactionmodel.userpostreactionmodel',
                     'postsharemodel.userpostsharemodel'
                     )->where('id', '=', $this->data['id'])
                     ->get();
      if (count($this->data) == 3) {
        return ["allDataPost" => $postData, "dataType" => $this->data['type'], 'post_comment_id' => $this->data['post_comment_id']];
      } else {
        return ["allDataPost" => $postData, "dataType" => $this->data['type']];
      }
    }
}
