@extends('popularity::index')
@section('content')
    <h2>Item List</h2>
      <ol class="list-group">
	@foreach ($items as $item)
	  <li class="list-group-item">
	    <ul>
	      <li>Last day hits: {{ $item->one_day_stats }}</li>
	      <li>Last Week hits: {{ $item->seven_days_stats }}</li>
	      <li>Last day hits: {{ $item->thirty_days_stats }}</li>
	      <li>All time hits: {{ $item->all_time_stats }}</li>
	      <li>Item data
		<ul>
		  @foreach ($item->trackable->getAttributes() as $key => $attr)
		    <li><strong>{{ $key }}</strong> : {{ $attr }}</li>
		  @endforeach
		</ul>
	      </li>
	    </ul>
	  </li>
	@endforeach
      </ol>
      
    {{ $items->links() }}
      
@stop
