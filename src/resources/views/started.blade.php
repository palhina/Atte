@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/started.css') }}">
@endsection

@section('content')
    <div class="index__content">
        <div class="index-title">
            <h2>{{ $user->name }}さんお疲れ様です！</h2>
        </div>
        <div class="index__button-container">
            <button class="index__button-punchIn" disabled>勤務開始</button>
            <form class="timestamp" action="/timeout" method="post">
            @csrf
                <button class="index__button-punchOut">勤務終了</button>
            </form>
            <form class="timestamp" action="/breakin/{attendance_id}" method="post">
            @csrf
                <button class="index__button-breakIn">休憩開始</button>
            </form>
            <button class="index__button-breakOut" disabled>休憩終了</button>    
        </div>
    </div>
@endsection