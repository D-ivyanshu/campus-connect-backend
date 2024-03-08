<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentCollection;

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

        return new CommentCollection($post->comments);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post, Comment $comment)
    {
        $data = $request->validate([
            'body' => 'required',
        ]);

        if ($comment->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update($data);

        return response()->json(['message' => 'Comment updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
