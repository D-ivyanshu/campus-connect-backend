<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Enums\ObjectReactionEnum;
use App\Http\Resources\PostCollection;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        // $posts = Post::with([
        //     'comments' => function ($query) use($userId){
        //         $query->withCount('reactions')
        //             ->with([
        //                 'reactions' => function ($query) use ($userId) {
        //                     $query->where('user_id', $userId);
        //                 }
        //             ]);
        //     }])->withCount('reactions')
        //     ->with([
        //         'comments' => function ($query) {
        //              $query->withCount('reactions');
        //         },
        //         'reactions' => function ($query) use ($userId) {
        //         $query->where('user_id', $userId);
        //     }])
        //     ->latest()
        //     ->get();

        $posts = Post::query()
            ->withCount('reactions')
            ->withCount('comments')
            ->with([
                'comments' => function ($query) use ($userId) {
                    $query->withCount('reactions')
                        ->with([
                            'reactions' => function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            }
                        ]);
                }
            ])
            ->with([
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->latest()
            ->get();

        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
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
        $userId = Auth::id();
        // $post = Post::with(['comments', 'media', 'reactions'])
        // ->withCount('reactions')
        // ->with(['reactions' => function ($query) use ($userId) {
        //     $query->where('user_id', $userId);
        // }])->find($post->id);

        $post = Post::withCount('reactions')
        ->withCount('comments')
        ->with([
            'comments' => function ($query) use ($userId) {
                $query->withCount('reactions')
                    ->with([
                        'reactions' => function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        }
                    ]);
            }
        ])
        ->with([
            'reactions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }
        ])
        ->find($post->id);

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

    public function postReaction(Request $request, Post $post) 
    {
        $data = $request->validate([
           'reaction' => [Rule::enum(ObjectReactionEnum::class)] 
        ]);

        $userId = Auth::id();
        $reaction = Reaction::where('user_id', $userId)->where('object_id', $post->id)->where('object_type', Post::class)->first();

        if($reaction) {
            $hasReaction = false;
            $reaction->delete();
        }
        else {
            $hasReaction = true;
            Reaction::create([
                'object_id' => $post->id,
                'object_type' => Post::class,
                'user_id' => Auth::id(),
                'type' => $data['reaction']
            ]);
        }

        $reactions = Reaction::where('object_id', $post->id)->where('object_type', Post::class)->count();

        return response([
            'success' => true,
            'reactions' => $reactions,
            'user_has_reaction' => $hasReaction
        ]);
    }
} 