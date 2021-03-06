<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Hashtag;
use App\Models\Page;
use App\Models\Post;
use App\Http\Resources\PageResource;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'post', 'listPosts']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\PageResource
     */
    public function index()
    {
        $pages = Page::simplePaginate(40);

        return PageResource::collection($pages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\PageResource
     */
    public function store(Request $request)
    {
        $page = Auth::user()->pages()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return new PageResource($page);
    }

    /**
     * Store a newly post 
     * 
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function createPost(Request $request, Page $page)
    {
        $post = $page->posts()->create([
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail),
            'privacy' => Post::ALL,
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
     * Edit post of page
     * 
     * @param  \App\Models\Page  $page
     * @param  \App\Models\Post  $post
     * @return App\Http\Resources\PageResource
     */
    public function editPost(Request $request, Page $page, Post $post)
    {
        $page->posts()->update([
            'title' => Str::title($request->title) ?? $post->title,
            'slug' => Str::slug($request->title) ?? $post->slug,
            'description' => $request->description ?? $post->description,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail) ?? $post->thumbnail,
            'privacy' => Post::ALL ?? $post->privacy,
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

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Delete post of page
     * 
     * @param  \App\Models\Page  $page
     * @param  \App\Models\Post  $post
     * @return App\Http\Resources\PageResource
     */
    public function deletePost(Page $page, Post $post)
    {
        $page->posts()->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function show(Page $page)
    {
        return new PageResource($page);
    }

    /**
     * Display the post of page.
     *
     * @param  \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function post(Page $page, Post $post)
    {
        $post->visits()->increment();
        
        return new PostResource($post);
    }

    /**
     * Display list posts of page
     * 
     * @param  \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function listPosts(Page $page)
    {
        $posts = $page->posts()->simplePaginate(40);

        return PostResource::collection($posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function update(Request $request, Page $page)
    {
        Auth::user()->pages()->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return App\Http\Resources\PageResource
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
