

<?php
$scopes = @$contacts->scopes ?? [];
?>
<div>
    <span><i class="las la-robot text-success"></i> {{ $contacts->is_bot == 1 ? 'Yes' : 'No' }}</span>
</div>
@if ($contacts->is_bot)
<ul class="list-unstyled list-inline">
    @foreach ($scopes as $scope)
            <li class="list-inline-item">
                <i class="las la-check-circle text-success"></i>
                {{ $scope }}
            </li>
    @endforeach
</ul>
 
@endif
