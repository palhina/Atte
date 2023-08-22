@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="index__content">
        <div class="index-title">
            <h2>『※名前を呼び出す』さんお疲れ様です！</h2>
        </div>
        <div class="index__button-container">
            <form class="timestamp" action="/timein" method="post">
            @csrf
                <button class="index__button-punchIn">勤務開始</button>
            </form>
            <button class="index__button-punchOut" disabled>勤務終了</button>
            <button class="index__button-breakIn" disabled>休憩開始</button>
            <button class="index__button-breakOut" disabled>休憩終了</button>  
        </div>
    </div>
@endsection