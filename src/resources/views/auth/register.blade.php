@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="register__content">
        <div class="register__group-title">
            <h2>会員登録</h2>
        </div>
        <form class="form" action="/register" method="post">
        @csrf
            <div class="register__form-content">
                <div class="form__name-input">
                    <div class="form__name-text">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="名前" />
                    </div>
                    <div class="form__error">
                        {{$errors->first('name')}}
                    </div>
                </div>
                <div class="form__email-input">
                    <div class="form__email-text">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="メールアドレス" />
                    </div>
                    <div class="form__error">
                        {{$errors->first('email')}}
                    </div>
                </div>
                <div class="form__pwd-input">
                    <div class="form__pwd-text">
                        <input type="password" name="password" placeholder="パスワード" />
                    </div>
                    <div class="form__error">
                        {{$errors->first('password')}}
                    </div>
                </div>
                <div class="form__pwd-input">
                    <div class="form__pwd-text">
                        <input type="password" name="password_confirmation" placeholder="確認用パスワード" />
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-register" type="submit">会員登録</button>
                </div>
            </div>
        </form>
        <div class="register__form-guidance">
            <div class="form__guidance-txt">
                <p>アカウントをお持ちの方はこちらから</p>
            </div>
            <div class="form__guidance-login">
                <a href="{{'/login'}}">ログイン</a>
            </div>
        </div>
    </div>
@endsection