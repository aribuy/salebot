@if(hasPermission('faqs.edit'))
    <div class="setting-check">
        <input type="checkbox" class="status-change"
               {{ ($faq->status == 1) ? 'checked' : '' }} data-id="{{ $faq->id }}"
               value="faq-status/{{$faq->id}}"
               id="customSwitch2-{{ $faq->id }}">
        <label for="customSwitch2-{{ $faq->id }}"></label>
    </div>
@endif
