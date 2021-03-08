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
        dd(Cashier::findBillable("cus_J4tmy5OzNaxpjg"));
        $limit = 24;
        if ($request->get('limit')) {
            $limit = $request->get('limit');
        }

        $videos = Video::paginate($limit);

        return view('video.index', compact('videos'));
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
