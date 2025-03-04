<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($page->status == 1) ? 'checked' : '' }} data-id="{{$page->id}}" value="pages-status/{{$page->id}}"
           id="customSwitch2-{{$page->id}}">
    <label for="customSwitch2-{{ $page->id }}"></label>
</div>