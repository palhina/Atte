@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/started.css') }}">
@endsection

@section('content')
    <div class="index__content">
        <div class="index-title">
            <h2>『※名前を呼び出す』さんお疲れ様です！</h2>
        </div>
        <div class="index__button-container">
            <button class="index__button-start">勤務開始</button>
            <button class="index__button-end">勤務終了</button>
            <button class="index__button-rest">休憩開始</button>
            <button class="index__button-continue">休憩終了</button>  
        </div>
    </div>
@endsection