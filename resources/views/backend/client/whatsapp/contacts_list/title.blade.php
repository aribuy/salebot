<div style="display: inline-block; vertical-align: top;" class="custom-control custom-checkbox contacts-list">
		<label class="custom-control-label"
		       for="{{$query->title}}">
			<input type="checkbox"
			       class="custom-control-input read common-key"
			       name="checkbox"
			       value="1"
			       id="{{$query->title}}">
			<span></span>
		</label>
</div>
<div style="display: inline-block; vertical-align: top;">
	<h6>{{ $query->title }}</h6>
	<p>{{__('created_at')}}: {{ $query->created_at->format('d/m/Y') }}</p>
</div>
