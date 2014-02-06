<div class="panel panel-default">
    <div class="panel-heading">Top List Widget</div>
      <ol class="list-gro/up">
	@foreach ($topItems as $item)
	  <li class="list-/group-item">
	    <ul>
	      <li>Last day hits: {{ $item->one_day_stats }}</li>
	      <li>Last Week hits: {{ $item->seven_day_stats }}</li>
	      <li>Last day hits: {{ $item->thirty_day_stats }}</li>
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
	  <hr />
	@endforeach
      </ol>
</div>
