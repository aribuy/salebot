<?php

if(!empty($contacts->avatar)){
    $avatar = $contacts->avatar;
}else{
    $avatar = static_asset('images/default/user.jpg');
}
?>
<div class="user-info-panel d-flex gap-12 align-items-center">
	<div class="user-img user-status}}">
		<img src="{{ $avatar }}" alt="{{ $contacts->name }}">
	</div>
	<div class="user-info">
		<p>{{@$contacts->name}}</p>
		<p>{{@$contacts->unique_id}}</p>
		<p>{{@$contacts->username}}</p>
	</div>
</div>
