<div class="setting-check">
    <input type="checkbox" class="status-change"
           {{ ($partner_logo->status == 1) ? 'checked' : '' }} data-id="{{ $partner_logo->id }}" value="partner-logo-status/{{$partner_logo->id}}"
           id="customSwitch2-{{$partner_logo->id}}">
    <label for="customSwitch2-{{ $partner_logo->id }}"></label>
</div>
