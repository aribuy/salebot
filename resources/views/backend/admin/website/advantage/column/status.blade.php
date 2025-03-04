<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($advantage->status == 1) ? 'checked' : '' }} data-id="{{ $advantage->id }}" value="website-advantage-status/{{$advantage->id}}"
           id="customSwitch2-{{$advantage->id}}">
    <label for="customSwitch2-{{ $advantage->id }}"></label>
</div>
