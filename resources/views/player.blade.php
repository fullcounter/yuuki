<!DOCTYPE html>
<html lang="en">
  <head>
@extends('layouts.header')
      <title>Yuuki - @foreach ($videodata as $data)
		  {{ $data['video_name'] }}
		@endforeach	</title>
    </head>
@section('content')
  <body class="bg-color" >
<!-- Base menu -->
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="https://yuuki.elscione.com/">Home</a>
  <form class="typeahead" role="search" style="padding: 5px 5px 5px 35px;">
      <div class="form-group">
        <input type="search" name="q" class="form-control search-input" placeholder="Search" autocomplete="off">
      </div>
    </form>
	
    <!-- Typeahead  -->
    <script>
        jQuery(document).ready(function($) {
            var engine = new Bloodhound({
                remote: {
                    url: '/find?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
                datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
                queryTokenizer: Bloodhound.tokenizers.whitespace
            });

            $(".search-input").typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                source: engine.ttAdapter(),

                // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
                name: 'video_name',

                // the key from the array we want to display (name,id,email,etc...)
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function (data) {
                        return '<a href=?video_id=' + data.id + ' class="list-group-item">' + data.video_name + '</a>'
              }
                }
            });
        });
    </script>
	
	
</div>


<!-- Menu button -->
<span onclick="openNav()" class="openbtn" id="obtn" ><i class="fa fa-bars" aria-hidden="true"></i>
</span>

<!-- Back button -->
 <span onclick="goBack()" class="backbtn"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>

	
<!-- Video-->
		@foreach ($videodata as $data)
		 <video class="now_playing" autoplay controls>
		  <source src="{{ $data['video_path'] }}" type="video/webm">
			Your browser does not support the video tag.
		</video> 
		@endforeach	
		

<!-- End Video -->
</body>
@endsection