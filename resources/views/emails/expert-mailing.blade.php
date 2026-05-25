@extends('emails.layout')

@section('content')

    @if(!empty($firstName))
        <p style="margin: 0 0 20px 0; font-size: 15px; color: #6b7280; line-height: 1.7;">
            Здравствуйте, <strong style="color: #1a1a1a;">{{ $firstName }}</strong>!
        </p>
    @endif

    <div style="font-size: 15px; color: #1a1a1a; line-height: 1.8;">
        {!! nl2br(e($body)) !!}
    </div>

@endsection
