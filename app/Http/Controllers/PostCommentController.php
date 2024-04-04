<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Enums\ObjectReactionEnum;
use App\Notifications\SendNotification;
use App\Http\Resources\CommentCollection;
use Illuminate\Support\Facades\Notification;

class PostCommentController extends Controller
{ 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Post $post) {
        
        $data = request()->validate([
            'body' => 'required'
        ]);

        $post->comments()->create(array_merge(
            $data, [
                'user_id' => auth()->user()->id,
            ]
        ));

        $post_user_id = auth()->user()->id;
        $post_user = User::find($post_user_id);

        $user = User::find($post->user_id);
        $title = 'commented on your post';
        // FIXME: check if the user has opt for the notfications
        Notification::send($user, new SendNotification('comment', $title, $post, $post_user, false));
        return new CommentCollection($post->comments);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, Comment $comment)
    {   
        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $data = $request->validate([
            'body' => 'required',
        ]);

        $comment->update($data);
        return response()->json(['message' => 'Comment updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function commentReaction(Request $request, Comment $comment) 
    {
        $data = $request->validate([
           'reaction' => [Rule::enum(ObjectReactionEnum::class)] 
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)->where('object_id', $comment->id)->where('object_type', Comment::class)->first();

        if($reaction) {
            $hasReaction = false;
            $reaction->delete();
        }
        else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $comment->id,
                'object_type' => Comment::class,
                'user_id' => Auth::id(),
                'type' => $data['reaction']
            ]);
        }

        $reactions = Reaction::where('object_id', $comment->id)->where('object_type', Comment::class)->count();

        return response([
            'success' => true,
            'reactions' => $reactions,
            'user_has_reaction' => $hasReaction
        ]);
    }
}
