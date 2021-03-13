<?php

namespace Rowles\Http\Controllers\Admin;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rowles\Console\Processors\MetadataProcessor;
use Rowles\Console\Processors\ThumbnailProcessor;
use Rowles\Http\Controllers\Controller;
use Rowles\Models\Video;

class ProcessingController extends Controller
{
    /** @var array|string[]  */
    protected static array $mappings = [
        'metadata.processor' => MetadataProcessor::class,
        'thumbnail.processor' => ThumbnailProcessor::class
    ];

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $videos = Video::all();
        $availableProcessors = array_keys(static::$mappings);

        return view('processing.index', compact('videos','availableProcessors'));
    }

    /**
     * @param string $processor
     * @param Request $request
     * @return JsonResponse
     */
    public function run(string $processor, Request $request): JsonResponse
    {
        if (!isset(static::$mappings[$processor])) {
            abort(500);
        }

        try {
            $process = app(static::$mappings[$processor])
                ->mapOptions($request->all())
                ->execute();

            $request->session()->flash('success', $processor . ' task executed successfully.');

            return response()->json($process);
        } catch (Exception $e) {
            $request->session()->flash('error', $e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
