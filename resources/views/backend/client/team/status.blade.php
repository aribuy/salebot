
    <div class="setting-check">
        <input type="checkbox" {{ $user->is_primary==1 ? 'disabled':'' }}  class="status-change"    {{ ($user->status == 1) ? 'checked' : '' }} data-id="{{$user->id}}"
               value="team-status/{{$user->id}}" id="customSwitch2-{{$user->id}}" >
        <label for="customSwitch2-{{ $user->id }}"></label>
    </div>

