<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowerCollection;
use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{

    // follows a user with $user->id we are the auth()->user()
    public function follow(User $user) {
        $follower = auth()->user();
        
        if ($follower->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }

        if ($follower->following->contains($user)) {
            return response()->json(['message' => 'You are already following this user'], 400);
        }

        $follower->following()->attach($user);
        // TODO: ADD A NOTIFICATION & EMAIL SYSTEM HERE 
        return response()->json(['message' => 'Followed Successfully'], 200);
    }

    public function unfollow(User $user) {
        $follower = auth()->user();
        
        if ($follower->id === $user->id) {
            return response()->json(['message' => 'You cannot unfollow yourself'], 400);
        }

        $follower->following()->detach($user);
        // TODO: ADD A NOTIFICATION & EMAIL SYSTEM HERE 
        return response()->json(['message' => 'Unfollowed Successfully'], 200);
    }

    public function followers(string $user_id) {
        $user = User::findOrFail($user_id);
        $followers = $user->followers;
        
        $followers->each(function ($follower) use ($user) {
            $follower->is_followed_by_user = $user->following->contains($follower->id);
        });
    
        return new FollowerCollection($followers);    
    }

    public function following(string $user_id) {
        $user = User::findOrFail($user_id);
        $following = $user->following;
        //TODO: change this collection 
        return new FollowerCollection($following);   
    }    

    public function isFollowed(User $user) {
        $follower = auth()->user();

        if($follower->id == $user->id) {
            return response()->json(['data' => '', 'message' => 'You are not allowed to follow yourself'], 200);
        }

        if ($follower->following->contains($user)) {
            return response()->json(['data' => 'unfollow','message' => 'You are already following this user'], 200);
        }
        return response()->json(['data' => 'follow','message' => 'You are not following this user'], 200);
    }
}
