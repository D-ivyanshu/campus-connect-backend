<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
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

    public function followers() {
        $user = auth()->user();
        $followers = $user->followers;
        return new UserCollection($followers);    
    }

    public function following() {
        $user = auth()->user();
        $following = $user->following;
        new UserCollection($following);   
    }

    public function isFollowed(User $user) {
        $follower = auth()->user();
        if ($follower->following->contains($user)) {
            return response()->json(['message' => 'You are already following this user'], 204);
        }

        return response()->json(['message' => 'You are not following this user'], 404);
    }

}
