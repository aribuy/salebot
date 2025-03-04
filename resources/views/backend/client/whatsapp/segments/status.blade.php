<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($segments->status == 1) ? 'checked' : '' }} data-id="{{$segments->id}}" value="segments-status/{{$segments->id}}"
           id="customSwitch2-{{$segments->id}}">
    <label for="customSwitch2-{{ $segments->id }}"></label>
</div>