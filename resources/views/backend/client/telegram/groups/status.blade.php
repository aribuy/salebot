<div class="setting-check">
	<input type="checkbox" class="status-change"
	       {{ ($query->status == 1) ? 'checked' : '' }} data-id="{{$query->id}}" value="telegram-groups-status/{{$query->id}}"
	       id="customSwitch2-{{$query->id}}">
	<label for="customSwitch2-{{ $query->id }}"></label>
</div>