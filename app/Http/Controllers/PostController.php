<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostCollection;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['comments' => function ($query) {
            $query->latest();
        }])->latest()->get();
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    //     $data = $request->except(['file']);
    //     $uploadedFileUrl = cloudinary()->uploadFile($request->file('file')->getRealPath())->getSecurePath();
    //     return $uploadedFileUrl;
        
    //     // $post = request()->user()->posts()->create($data);
    //     // return new PostResource($post);
    // }
    
    public function store(Request $request){

        $uploadedFiles = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $uploadedFileUrl = cloudinary()->uploadFile($file->getRealPath())->getSecurePath();
                $uploadedFiles[] = [
                    'file_url' => $uploadedFileUrl,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }
    
        $postData = $request->except(['media']);
        $post = request()->user()->posts()->create($postData);
        
        foreach ($uploadedFiles as $fileData) {
            $media = new Media($fileData);
            $media->medially()->associate($post);
            $media->save();
        }
        
        return new PostResource($post);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post = Post::with(['comments', 'media'])->find($post->id);
        return new PostResource($post);
    }

    public function userPost(string $user_id) {
        $user = User::findOrFail($user_id);
        $posts = $user->posts()->latest()->get();
        // return response()->json($posts);
        return new PostCollection($posts);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    { 
        $data = request()->validate([
            'body' => 'required|string',
        ]);

        $post->update($data);
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
} 