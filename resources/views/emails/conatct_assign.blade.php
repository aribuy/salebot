@extends('emails.layout.app')
@section('title', '')
@section('content')
<p style="margin-bottom: 25px; color: ;">{!! $subject !!}</p>
<p style="margin-bottom: 25px; color: ;">{!! $email_templates !!}</p>
@endsection