<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($feature->status == 1) ? 'checked' : '' }} data-id="{{ $feature->id }}" value="website-unique-feature-status/{{$feature->id}}"
           id="customSwitch2-{{$feature->id}}">
    <label for="customSwitch2-{{ $feature->id }}"></label>
</div>
