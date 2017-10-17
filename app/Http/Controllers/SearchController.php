<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Helpers\MainHelper;

class SearchController extends Controller
{
    public function find(Request $request)
{
	$data = Video::select('*')->where('video_name', 'like', '%' . $request->get('q') . '%')->get();
    return $data;
}
}
