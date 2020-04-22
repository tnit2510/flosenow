<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\PostResource
     */
    public function index()
    {
        $posts = Post::with(['hashtags' => function ($q) {
            $q->paginate(20);
        }])->paginate(60);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return App\Http\Resources\PostResource
     */
    public function store(PostRequest $request)
    {
        $post = Auth::user()->posts()->create([
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'thumbnail' => self::handleUploadedImage($request->thumbnail),
        ]);

        $hashtags = explode(', ', $request->hashtags);
        $arrHashtagId = [];
        foreach ($hashtags as $hashtag) {
            $data = Hashtag::firstOrCreate([
                'name' => $hashtag,
                'slug' => Str::slug($hashtag),
            ]);

            $arrHashtagId[] = $data->id;
        }

        $post->hashtags()->sync($arrHashtagId);

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return App\Http\Resources\PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Post  $post
     * @return App\Http\Resources\PostResource
     */
    public function update(PostRequest $request, Post $post)
    {
        Auth::user()->posts()->update([
            'title' => Str::title($request->title) ?? $post->title,
            'slug' => Str::slug($request->title) ?? $post->slug,
            'description' => $request->description ?? $post->description,
            'thumbnail' => self::handleUploadedImage($request->thumbnail) ?? $post->thumbnail,
        ]);

        $hashtags = explode(', ', $request->hashtags);
        $arrHashtagId = [];
        foreach ($hashtags as $hashtag) {
            $data = Hashtag::firstOrCreate([
                'name' => $hashtag,
                'slug' => Str::slug($hashtag),
            ]);

            $arrHashtagId[] = $data->id;
        }

        $post->hashtags()->sync($arrHashtagId);

        return response()->json(['message' => 'Chỉnh sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['message' => 'Xoá bài viết thành công!!!']);
    }

    public static function handleUploadedImage($image)
    {
        $name = Str::random(10) . time() . '.jpg';

        if (!is_null($image)) {
            $image->move(public_path('thumbnails'), $name);

            return $name;
        }
    }
}
