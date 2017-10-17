<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video';
    protected $primaryKey = "id"; 	
   	protected $guarded = array('id');
    public $timestamps  = false;
}