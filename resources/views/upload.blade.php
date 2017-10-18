<!DOCTYPE html>
<html lang="en">
  <head>
@extends('layouts.header')
      <title>Yuuki - Upload Video</title>
    </head>
@section('content')
<body>
    @extends('layouts.navbar')
<div class="container">
    <br>
    <h1>Upload Video</h1>
  	<hr>
	<div class="row">
            <form class="form-horizontal col-md-12" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                        <h4>Video</h4>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                    Browse&hellip; <input type="file" name="video" style="display: none;" multiple>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="video_verif" readonly>
                        </div>
                        <span class="help-block">
                            Choose video (.webm/mp4 extension, with opus audio and h264/vp9/vp8 video.)
                        </span>
                    </div>
                
                
                    <!-- Description -->
                    <h3>Description</h3>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Song Name:</label>
                            <div class="col-md-8">
                                <input class="form-control" value="" type="text" name="song_name" required>
                            </div>
                        </div>
                
                
                        <div class="form-group">
                            <label class="col-md-3 control-label">Anime Name and OP/ED number:</label>
                            <div class="col-md-8">
                                <input class="form-control" value="" type="text" name="anime_name" required>
                            </div>
                        </div>
                
                
                
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                        <div class="col-md-8">
                            <input class="btn btn-primary" value="Save Changes" type="submit">
                            <span></span>
                            <input class="btn btn-default" value="Cancel" type="reset">
                        </div>
                    </div>
            </form>   
                </div>
                
            <div class="col-md-9 personal-info">
                
            </div>
  </div>
<hr>
       </body>    
@endsection