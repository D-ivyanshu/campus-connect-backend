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

        $data = $request->validate([
            'about' => 'string',
            'avatar' => 'required|string',
            'branch' => 'string',
            'course' => 'string',
            'username' => 'string',
            'year' => 'string',
        ]);

        $socialLinks = $request->only(['facebook', 'linkedin', 'instagram', 'twitter']);

        $data = $request->except(['facebook', 'linkedin', 'instagram', 'twitter']);

        if (!empty($socialLinks)) {
            $updateData = array_merge($data, ['social_links' => $socialLinks]);
        } else {
            $updateData = $data;
        }

        $user->update($updateData);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
    

    public function uploadSingleFile(Request $request){
 
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $uploadedFileUrl = cloudinary()->uploadFile($file->getRealPath())->getSecurePath();
            $uploadedFiles = [
                'file_url' => $uploadedFileUrl,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'size' => $file->getSize()
            ];
        }
    
        return response()->json([
            'data' => $uploadedFiles
        ]);
    }

}
