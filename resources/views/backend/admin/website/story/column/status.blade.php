<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($story->status == 1) ? 'checked' : '' }} data-id="{{ $story->id }}" value="website-story-status/{{$story->id}}"
           id="customSwitch2-{{$story->id}}">
    <label for="customSwitch2-{{ $story->id }}"></label>
</div>
