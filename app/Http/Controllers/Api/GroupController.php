<?php

namespace App\Http\Controllers\Api;

use Auth;
use Str;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Post;
use App\Models\Hashtag;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;

class GroupController extends Controller
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
     * @return \App\Http\Resources\GroupResource
     */
    public function index()
    {
        $groups = Group::paginate(40);

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @return \App\Http\Resources\GroupResource
     */
    public function store(GroupRequest $request)
    {
        $group = Auth::user()->groups()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'privacy' => $request->privacy,
        ]);

        return new GroupResource($group);
    }

    /**
     * Store a newly post 
     * 
     * @param \App\Http\Requests\GroupRequest  $request
     * @param \App\Models\Group  $group
     * @return \App\Http\Resources\PostResource
     */
    public function createPost(GroupRequest $request, Group $group)
    {
        $post = $group->posts()->create([
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail),
            'privacy' => $request->privacy,
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
     * @param  \App\Models\Group  $group
     * @return \App\Http\Resources\GroupResource
     */
    public function show(Group $group)
    {
        return new GroupResource($group);
    }

    /**
     * Display the post of group.
     *
     * @param  \App\Models\Group  $group
     * @return App\Http\Resources\GroupResource
     */
    public function post(Group $group, Post $post)
    {
        $post->visits()->increment();
        
        return new PostResource($post);
    }

    /**
     * Display list posts of group
     * 
     * @param  \App\Models\Group  $group
     * @return App\Http\Resources\GroupResource
     */
    public function listPosts(Group $group)
    {
        $posts = $group->posts()->paginate(40);

        return GroupResource::collection($posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \App\Http\Resources\GroupResource
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'privacy' => $request->privacy,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
