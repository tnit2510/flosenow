<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Resources\AnnouncementResource;

class AnnouncementController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role_or_permission:supervisor|create announcements', ['only' => 'store']);
        $this->middleware('role_or_permission:supervisor|edit announcements', ['only' => 'update']);
        $this->middleware('role_or_permission:supervisor|delete announcements', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::simplePaginate(30);

        return AnnouncementResource::collection($announcements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $announcement = Announcement::create([
            'label' => $request->label,
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail),
        ]);

        return new AnnouncementResource($announcement);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return new AnnouncementResource($announcement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        $announcement->update([
            'label' => $request->label,
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail),
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
