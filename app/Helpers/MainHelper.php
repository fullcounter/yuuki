<?php

namespace App\Helpers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Thumbnail;
use App\Models\Video;
use App\User; 
use Session;
use Auth;

class MainHelper {
    

    public function __construct() 
    {
        
    }
    
	function getVideoList(){
		$data = Video::select('*')->orderBy('id', 'desc')->get();
        $data = $data->toArray();
        
        return $data;
	}

	function getVideo($video_id){
		$data = Video::select('*')->where('id', '=', $video_id)->get();
        $data = $data->toArray();
        return $data;
	}
	
	function getRandomVideoList(){
		$data = Video::select('*')->inRandomOrder()->take(10)->get();
        $data = $data->toArray();
        return $data;
	}
    
    function setVideoData($request){
        $video = $request->file('video');
        $extension_type   = $video->getClientMimeType();
        $extension        = $video->getClientOriginalExtension();
        $destination_path = public_path().'/video/';
        $upload_status    = $video->move($destination_path, $video->getClientOriginalName());     
        
        if($upload_status)
            {
              // set storage path to store the file (image generated for a given video)
              $thumbnail_path   = public_path().'/images';

              $video_path       = $destination_path.$video->getClientOriginalName();

              // set thumbnail image name
              $thumbnail_image  = $video->getClientOriginalName().".jpg";

              $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,5);
            }
        $data = new Video;
        $data->video_name = $request->input('anime_name');
        $data->video_path = '/video/'.$video->getClientOriginalName();
        $data->video_date = "1";
        $data->video_thumbnail = '/images/'.$video->getClientOriginalName().'.jpg';
        $data->save();
        return true;
    }
        
        
        
        
	
    function listIklanTerbaru(){
        $data = Iklan::select('*')->orderBy('created_at', 'desc')->take(5)->get();
        foreach($data as $town){
            $town->kategori =  $this->getKategoriString($town->kategori);
        }
        $data = $data->toArray();
        return $data;
    }
    
    function cariIklan($keywords, $kategori){ 
        
        // Cari iklan berdasarkan $keywords, dan ownernya berdasarkan id_user pada tabel iklan
        $hasil = DB::table('iklan')
                ->join('users', function ($join) {
                 $join->on('users.id', '=', 'iklan.id_user');
                })
                ->where('judul_iklan', 'LIKE', '%'.$keywords.'%', 'OR', 'deskripsi', 'LIKE', '%'.$keywords.'%', 'AND', 'iklan.kategori', '=', $kategori)
                ->orderBy('id_iklan', 'DESC')
                ->paginate(5, ['*'], 'halaman');
        return $hasil;
    }
    
    function getIklanWithKategori($kategori, $paginate = 5 ){ 
        $pagination   = isset($paginate) ? $paginate : 5;
        // Cari iklan berdasarkan kategori
        if($kategori == 0){
            $hasil = DB::table('iklan')
                ->join('users', function ($join) {
                    
                 $join->on('users.id', '=', 'iklan.id_user');
                })
                ->orderBy('id_iklan', 'DESC')
                ->paginate($pagination, ['*'], 'halaman');
        } else {
            $hasil = DB::table('iklan')
                ->join('users', function ($join) {
                 $join->on('users.id', '=', 'iklan.id_user');
                })
                ->where('kategori', '=', $kategori)
                ->orderBy('id_iklan', 'DESC')
                ->paginate($pagination, ['*'], 'halaman');
        }
        
        return $hasil;
    }
    
    // TAMPILKAN IKLAN //
    function tampilkanIklan($id_iklan){     
        $iklan = Iklan::where('id_iklan', '=', $id_iklan)->first()->toArray();
        $pemilik = Owner::where('id', '=', $iklan['id_user'])->first()->toArray();
        $komentar = DB::table('komentar')        
            ->join('users', 'users.id', '=', 'komentar.id_user')
            ->join('iklan', 'iklan.id_iklan', '=', 'komentar.id_iklan')
            ->where('komentar.id_iklan', '=', $id_iklan)
            ->orderBy('komentar.id', 'DESC')
            ->get();
        $iklan['kategori'] = $this->getKategoriString($iklan['kategori']); // Ubah angka kategori ke string
        return array($iklan, $pemilik, $komentar);
    }
    
    
    // PASANG IKLAN //
    function pasangIklan(Request $request){
        Validator::make($request->all(), [
                'judul_iklan'     => 'required|min:6',
                'harga' => 'required|min:1',
                'foto' => 'required|mimes:jpg,jpeg,bmp,png',
                'is_new' => 'required',
                'kategori' => 'required',
                'deskripsi' => 'required',
            ])->validate();
        $pathFoto = $this->uploadFoto($request);
        $current_time = Carbon::now()->toDateTimeString();
        // $kategoriString = $this->getKategoriString($request->input('kategori'));
        DB::table('iklan')->insert([
                'id_user' => Auth::user()->id,
                'judul_iklan' => $request->input('judul_iklan'),
                'foto' => $pathFoto,
                'harga' => $request->input('harga'),
                'is_new' => $request->input('is_new'),
                'is_verified' => 0,
                'kategori' => $request->input('kategori'),
                'deskripsi' => $request->input('deskripsi'),
                'created_at' => $current_time,
            ]);
        $id = Iklan::orderBy('id_iklan', 'DESC')->first()->toArray();
        return $id;
    }
    
    // EDIT IKLAN //
    function updateIklan(Request $request, $id){
        Validator::make($request->all(), [
                'judul_iklan'     => 'required|min:6',
                'harga' => 'required|min:1',
                'foto' => 'required|mimes:jpg,jpeg,bmp,png',
                'is_new' => 'required',
                'kategori' => 'required',
                'deskripsi' => 'required',
            ])->validate();
        $pathFoto = $this->uploadFoto($request);
        $current_time = Carbon::now()->toDateTimeString();
        $data = Iklan::where('id_iklan', '=', $id)
            ->update([
                'judul_iklan' => $request->input('judul_iklan'),
                'foto' => $pathFoto,
                'harga' => $request->input('harga'),
                'is_new' => $request->input('is_new'),
                'kategori' => $request->input('kategori'),
                'deskripsi' => $request->input('deskripsi'),
                'modified_at' => $current_time,
            ]);
        $id = Iklan::orderBy('modified_at', 'DESC')->first()->toArray();
        return $id;
    }
    
    
    
    function uploadFoto(Request $request){
        if($request->file('foto')){
            $foto = $request->file('foto');
        } else if ($request->file('avatar_user')){
            $foto = $request->file('avatar_user');
        }
        $fileName = rand(1, 999) . $foto->getClientOriginalName();
        $path = $foto->storeAs('foto/'.Auth::user()->id.'', $fileName, 'uploads');
        return $path;
    }
    
    
    function ambilDataProfil($id){
        $data = Owner::where('id', '=', $id)->first();
        return $data;
    }
    
    function ambilIklanUser($id){
        $data = Iklan::where('id_user', '=', $id)->paginate(5, ['*'], 'halaman');
        return $data;
    }
    
    function editProfil(Request $request){
        // VERIFIKASI INPUT FORM, RETURN ERROR JIKA ADA YANG SALAH //
        Validator::make($request->all(), [
                'nama'     => 'required',
                'email' => 'required|email',
                'alamat' => 'required',
                'avatar_user' => 'mimes:jpeg,bmp,png',
                'tanggal_lahir' => 'required|date',
                'nomor_telp' => 'required',
            ])->validate();
        
        // CEK APAKAH GANTI AVATAR
       if($request->input('avatar_user_verif') != null){
            $pathFoto = $this->uploadFoto($request);
            $data = Owner::where('id', '=', Auth::user()->id)
            ->update([
                'name' => $request->input('nama'),
                'email' => $request->input('email'),
                'avatar_user' => $pathFoto,
                'alamat' => $request->input('alamat'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'nomor_telp' => $request->input('nomor_telp'),
            ]);
           $status = "Data berhasil di edit!";
        } else {
            $data = Owner::where('id', '=', Auth::user()->id)
            ->update([
                'name' => $request->input('nama'),
                'email' => $request->input('email'),
                'alamat' => $request->input('alamat'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'nomor_telp' => $request->input('nomor_telp'),
            ]);
           $status = "Data berhasil di edit!";
        }
        return $status;
    }
    
    function hapusUser($id){
        $data = User::where('id', '=', $id)
            ->delete();
        $iklan = Iklan::where('id_user', '=', $id)
            ->delete();
        return true;
    }
  
    
    // VALIDATE PASSWORD, KALO SALAH DIA THROW ERROR DAN BALIK LAGI KE FORM
    function validasiPassword(Request $request){
            Validator::make($request->all(), [
                'oldpass'     => 'required|min:6',
                'newpass'     => 'required|min:6',
            ])->validate();
            $data = $request->all();
            $user = User::find(auth()->user()->id);
            if (Hash::check($data['oldpass'], $user->password)) {
                Auth::user()->password = bcrypt($data['newpass']);
                Auth::user()->save();
                $status = "Password berhasil diganti!";
                return $status;
            }
            $status = "Password lama anda salah!";
            return $status;
    }
    
    function postComment($id_iklan, $id_user, $request){
        $komentar = new Komentar;
        $komentar->id_user = $id_user;
        $komentar->id_iklan = $id_iklan;
        $komentar->komentar = $request->input('isi');
        $komentar->timestamp = Carbon::now()->toDateTimeString();
        $komentar->save();
        $status = "Komentar telah ditambahkan";
        return $status;
        }
    
    function sendPm($request, $id_pengirim){
        Validator::make($request->all(), [
                'username'     => 'required',
                'isi'     => 'required',
            ])->validate();
        $data = $request->all();
        $id_penerima = $this->getUserId($data['username']);
        $pm = new PrivateMessage;
        $pm->id_pengirim = $id_pengirim;
        $pm->id_penerima = $id_penerima['id'];
        $pm->isi = $data['isi'];
        $pm->save();
        return true;
    }
    
    function getUserList($pagination){
        $data = User::select('*')
            ->paginate($pagination, ['*'], 'halaman');
        return $data;
    }
    
    function getUsername($id){
        $data = User::select('username')
            ->where('id', '=', $id)->first();
        return $data;
    }
    
    function getUserId($username){
        $data = User::select('id')
            ->where('username', '=', $username)->first();
        return $data;
    }
    
    function getKategoriString($id){
        $data = Kategori::where('id', '=', $id)
            ->first();
        return $data;
    }
    
    function getUserCount(){
        $data = User::select('*')->get();
        $data = count($data);
        return $data;
    }
    
    function getBannedUserCount(){
        $data = User::select('*')
            ->where('is_banned', '=', '1')
            ->get();
        $data = count($data);
        return $data;
    }
    
    function getPostedIklanCount(){
        $data = Iklan::select('*')->get();
        $data = count($data);
        return $data;
    }
    
    
    function getVerifiedIklanCount(){
        $data = Iklan::select('*')
            ->where('is_verified', '=', '0')
            ->get();
        $data = count($data);
        return $data;
    }
    
    function verifyIklan($id){
        $data = Iklan::where('id_iklan', '=', $id)
            ->update([
                'is_verified' => 1,
            ]);
        return true;
    }
    
    function unverifyIklan($id){
        $data = Iklan::where('id_iklan', '=', $id)
            ->update([
                'is_verified' => 0,
            ]);
        return true;
    }
    
    function hapusIklan($id){
        $data = Iklan::where('id_iklan', '=', $id)
            ->delete();
        return true;
    }
    
}


?>