<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Http\Controllers\Controller;
use App\Models\Anouncement;
use Illuminate\Http\Request;
use App\Http\Resources\AnouncementResource;

class AnouncementController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
        $this->middleware('role_or_permission:supervisor|create anouncements', ['only' => 'store']);
        $this->middleware('role_or_permission:supervisor|edit anouncements', ['only' => 'update']);
        $this->middleware('role_or_permission:supervisor|delete anouncements', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anouncements = Anouncement::paginate(30);

        return AnouncementResource::collection($anouncements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $anouncement = Anouncement::create([
            'label' => $request->label,
            'title' => Str::title($request->title),
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail),
        ]);

        return new AnouncementResource($anouncement);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anouncement  $anouncement
     * @return \Illuminate\Http\Response
     */
    public function show(Anouncement $anouncement)
    {
        return new AnouncementResource($anouncement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anouncement  $anouncement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anouncement $anouncement)
    {
        $anouncement->update([
            'label' => $request->label ?? $anouncement->label,
            'title' => Str::title($request->title) ?? $anouncement->title,
            'slug' => Str::slug($request->title) ?? $anouncement->slug,
            'content' => $request->content ?? $anouncement->content,
            'thumbnail' => PostController::handleUploadedImage($request->thumbnail) ?? $anouncement->thumbnail,
        ]);

        return response()->json(['message' => 'Sửa thành công!!!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anouncement  $anouncement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anouncement $anouncement)
    {
        $anouncement->delete();

        return response()->json(['message' => 'Xóa thành công!!!']);
    }
}
