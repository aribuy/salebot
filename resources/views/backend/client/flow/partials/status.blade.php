<div class="setting-check">
	<input type="checkbox" class="status-change" {{ ($flow->status == 1) ? 'checked' : '' }} data-id="{{ $flow->id }}"
	       value="flow-builder-status/{{ $flow->id }}" id="customSwitch2-{{ $flow->id }}">
	<label for="customSwitch2-{{ $flow->id }}"></label>
</div>

