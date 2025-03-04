@if(hasPermission('client.edit'))
    <div class="setting-check">
        <input type="checkbox" class="status-change" data-id="{{ $client->id }}"
               {{ ($client->status == 1) ? 'checked' : '' }} value="clients-status/{{$client->id}}"
               id="customSwitch2-{{$client->id}}">
        <label for="customSwitch2-{{ $client->id }}"></label>
    </div>
@endif
