@extends('layouts.header')

<<<<<<< HEAD
@section('content')
=======
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins  and Typeahead) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Typeahead.js Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
	<script>
/* Set the width of the side navigation to 250px */
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
	document.getElementById("obtn").style.visibility = "hidden";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
	document.getElementById("obtn").style.visibility = "visible";
} 
function goBack() {
    window.history.back();
}
</script>
	<style>
	
.now_playing {
	-webkit-transform: translateX(-50%) translateY(-50%);
    -moz-transform: translateX(-50%) translateY(-50%);
    -ms-transform: translateX(-50%) translateY(-50%);
    -o-transform: translateX(-50%) translateY(-50%);
    transform: translateX(-50%) translateY(-50%);
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 90%;
    min-height: 90%;
    width: 100vw; /* Could also use width: 100%; */
    height: 100vh;
	
}
.bg-color {
	background-color: black;
}

 /* The side navigation menu */
.sidenav {
    height: 100%; 
    width: 0; 
    position: fixed; 
    z-index: 1; /* Stay on top */
    top: 0;
    right: 0;
    background-color: #111; /* Black*/
	opacity: 0.8;
    overflow-x: hidden; /* Disable horizontal scroll */
    padding-top: 60px; /* Place content 60px from the top */
    transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
}

/* The navigation menu links */
.sidenav a {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    transition: 0.3s
}

/* When you mouse over the navigation links, change their color */
.sidenav a:hover, .offcanvas a:focus{
    color: #f1f1f1;
}

/* Position and style the close button (top right corner) */
.sidenav .closebtn {
    position: absolute;
    top: 0;
    right: 25px;
    font-size: 36px;
    margin-left: 0px;
}

.openbtn {
	float: left;
    position: absolute;
    top: 0;
	color: white;
    right: 25px;
    font-size: 36px;
    margin-left: 0px;
	z-index : 100;
	cursor: pointer;
}

.backbtn {
	position: absolute;
    top: 0;
	color: white;
    left: 25px;
    font-size: 36px;
    margin-left: 0px;
	z-index : 100;
	cursor: pointer;
}
.search {
	padding: 8px 8px 8px 32px;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
    transition: margin-left .5s;
    padding: 20px;
}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
} 

</style>
  </head>
>>>>>>> parent of bbfd29d... replace tested assets load
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
		  <source src="{{ asset('video/[UTW]_Fate_Apocrypha_-_OP2_[h264-720p][6B54E188].webm') }}" type="video/webm">
			Your browser does not support the video tag.
		</video> 
		@endforeach	
		

<!-- End Video -->
</body>
@endsection