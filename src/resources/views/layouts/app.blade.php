<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-ttl">
            <h1>Atte</h1>
        </div>
        <nav class="header-nav">
            <ul class="header-nav-list">
                @if (Auth::check())
                    <li class="header-nav-item"><a href="{{'/'}}">ホーム</a></li>
                    <li class="header-nav-item"><a href="{{'/attendance'}}">日付一覧</a></li>
                    <li class="header-nav-item"><a href="{{'/users'}}">ユーザー一覧</a></li>
                    <li class="header-nav-item"><a href="{{'/user_atte'}}">ユーザー別勤怠一覧</a></li>
                    <li class="header-nav-item"> 
                        <form class="form" action="/logout" method="post">
                        @csrf
                            <button class="header-nav__button">ログアウト</button>
                        </form>
                    </li>
                @endif
            </ul>
        </nav>
    </header>

    <main class="main">
        @yield('content')
    </main>
    <footer class="footer">
        <small class="copyright">
            Atte,inc. 
        </small>
    </footer>
</body>
    
</html>