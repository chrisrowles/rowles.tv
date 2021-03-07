<?php

namespace Rowles\Http\Controllers\Api;

use Rowles\Models\Video;
use Illuminate\Http\JsonResponse;
use Rowles\Http\Controllers\Controller;
use Rowles\Http\Requests\UpdateVideoRequest;


class VideoController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $videos = Video::with('metadata')->get();

        if (!$videos) {
            abort(404);
        }

        return response()->json($videos);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $video = Video::with('metadata')->where('id', $id)->first();

        if (!$video) {
            abort(404);
        }

        return response()->json($video);
    }

    /**
     * @param $id
     * @param UpdateVideoRequest $request
     * @return JsonResponse
     */
    public function update($id, UpdateVideoRequest $request): JsonResponse
    {
        $video = Video::where('id', $id)->first();

        if (!$video) {
            abort(400);
        }

        $validated = $request->validated();

        $video->title = $validated['title'];
        $video->genre = $validated['genre'];
        $video->description = $validated['description'];

        if (!$video->save()) {
            abort(500);
        }

        return response()->json($video);
    }
}
