<?php

namespace Rowles\Http\Controllers;

use Laravel\Cashier\Cashier;
use Rowles\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        $limit = 24;
        if ($request->get('limit')) {
            $limit = $request->get('limit');
        }

        $videos = Video::paginate($limit);

        // Todo, create category and producer model
        $categories = ["Test One", "Test Two", "Test Three"];
        $producers = ["Test One", "Test Two", "Test Three"];

        return view('video.index', compact('videos', 'categories', 'producers'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $videos = Video::search($request->all());

        $request->flash();

        return view('video.index', compact('videos'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function watch($id)
    {
        $video = Video::where('id', $id)->first();
        $related = Video::related($video);

        if (!$video) {
            abort(404);
        }

        return view('video.watch', compact('video', 'related'));
    }
}
