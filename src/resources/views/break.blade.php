@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/break.css') }}">
@endsection

@section('content')
    <div class="index__content">
        <div class="index-title">
            <h2>{{ $user->name }}さんお疲れ様です！</h2>
        </div>
        <div class="index__button-container">
            <button class="index__button-punchIn" disabled>勤務開始</button>
            <button class="index__button-punchOut" disabled>勤務終了</button>
            <button class="index__button-breakIn"  disabled>休憩開始</button>
            <form class="timestamp" action="/breakout" method="post">
            @csrf
                <button class="index__button-breakOut">休憩終了</button>  
            </form> 
        </div>
    </div>
@endsection