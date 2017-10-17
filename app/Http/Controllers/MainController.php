<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Session;
use Auth;
// Table
use App\Models\Video;

// Helpers
use App\Helpers\MainHelper;

class mainController extends Controller {
    
    public function __construct()
	{
        $this->home = new MainHelper();
	}

      public function errors()
        {
        return view('errors.503');
    }
    
	public function index(Request $request)
    {
		
        $video_id         = isset($_GET['video_id']) ? $_GET['video_id'] : 0;
		$video_list = $this->home->getVideoList();
        if($video_id != null){
                if ($video_id < 1){
                    // If not valid value Video ID
                    return redirect('/')->with('status', 'Video not found!');
                } else {
                    // If valid, load data from database with helper
                    $videodata = $this->home->getVideo($video_id); 
                    if($videodata == null){
		              return redirect('/');  
                    }
					$randomvideo = $this->home->getRandomVideoList();
                    return view('player')->with('videodata', $videodata)
										 ->with('randomvideo', $randomvideo);
            }   
            return view('index')->with('video_list', $video_list);  
		}
		return view('home')->with('video_list', $video_list);
    }
    
    
    public function upload(Request $request)
    {
		return view('upload');
    }
    
    
    
    
    public function postupload(Request $request)
    {
		          $video_list = $this->home->getVideoList();
                $data = $this->home->setVideoData($request);
                return view('home')->with('video_list', $video_list);
                    
}
}