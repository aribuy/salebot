<div class="setting-check">
	<input type="checkbox" class="status-change"
	       {{ ($contacts->status == 1) ? 'checked' : '' }} data-id="{{$contacts->id}}" value="contact-status/{{$contacts->id}}"
	       id="customSwitch2-{{$contacts->id}}">
	<label for="customSwitch2-{{ $contacts->id }}"></label>
</div>