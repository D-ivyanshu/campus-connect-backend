<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUserProfile(Request $request, User $user)
    {
        $authenticated_user = Auth::id();
        if($authenticated_user != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $data = $request->except('avatar');
        $avatar = $request->file('avatar');
        if ($avatar) {
            $avatarPath = $avatar->store('avatar', 'public');
            $data['avatar'] = "http://localhost:8000/storage/$avatarPath";
        }
        $user->update($data);

        return response()->json(['user' => $user], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
