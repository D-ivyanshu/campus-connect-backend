<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

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
