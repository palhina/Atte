@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify_email.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card_txt">
        @if (session('resent'))
                <div class="alert_success" role="alert">
                    {{ __('メールを再送信しました') }}
                </div>
        @endif
        <div class="card_header">{{ __('二段階認証') }}</div>
        <div class="card_body">
            <div class="email_text">
            <p>{{ __('登録されたアドレスにメールを送信しました。') }}</p>
            <p>{{ __('メール内のリンクを確認してください。') }}</p>
            <p>{{ __('メールが届かない場合は以下のボタンを押してください。') }}</p>
            <form class="mail_resend_btn" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="mail_resend_link">{{ __('メール再送信') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection