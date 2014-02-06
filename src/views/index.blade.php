<html>
  <head>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="page-header">
      <h1>Popularity Main Page example</h1>
    </div>
    <div class="row">
      <div class="col-md-8">
	<ul class="nav nav-tabs">
	  <li class="{{ (Route::current()->getName() == 'popularity' && Request::segment(2) == 'day') ? 'active' : null }}">
	    <a href="{{ route('popularity','day') }}">Last day</a>
	  </li>
	  <li class="{{ (Route::current()->getName() == 'popularity' && Request::segment(2) == 'week') ? 'active' : null }}">
	    <a href="{{ route('popularity','week') }}">Last week</a>
	  </li>
	  <li class="{{ (Route::current()->getName() == 'popularity' && Request::segment(2) == 'month') ? 'active' : null }}">
	    <a href="{{ route('popularity','month') }}">Last month</a>
	  </li>
	  <li class="{{ (Route::current()->getName() == 'popularity' && Request::segment(2) == 'all_time') ? 'active' : null }}">
	    <a href="{{ route('popularity','all_time') }}">All time most popular</a>
	  </li>
	</ul>
	@yield('content', 'No content yet')
      </div>
      <div class="col-md-4">
	@include('popularity::widget')
      </div>
    </div>

  </body>
</html>
