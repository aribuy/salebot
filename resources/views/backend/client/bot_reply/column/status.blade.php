<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($reply->status == 1) ? 'checked' : '' }} data-id="{{$reply->id}}" value="botReplay-status/{{$reply->id}}"
           id="customSwitch2-{{$reply->id}}">
    <label for="customSwitch2-{{ $reply->id }}"></label>
</div>