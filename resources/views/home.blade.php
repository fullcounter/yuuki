@extends('layouts.header')
@section('content')
  <body>
  @extends('layouts.navbar')

<!-- Container -->
	<div class="container">

      <h1 class="my-4 text-center text-lg-left">OP/ED List <br><form class="typeahead form-inline my-2 my-lg-0" role="search">
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
    </script></h1>
	  
	  
      <div class="row text-center text-lg-left">
	  
		
		@foreach ($video_list as $data)
		
		
        <div class="col-lg-3 col-md-4 col-xs-6">
          
          <a href="/?video_id={{ $data['id'] }}" class="d-block mb-4 h-100">
                @if (empty($data['video_thumbnail']))
            
            <img class="img-fluid img-thumbnail" src="http://placehold.it/400x300" alt="">
                    @else
            <img class="img-fluid img-thumbnail" src="{{ $data['video_thumbnail'] }}" alt="">
                    @endif
          </a>
        </div>
		@endforeach
      </div>

    </div>
<!-- End Container -->
      @endsection
