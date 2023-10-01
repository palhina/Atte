#アプリケーション名
Atte（アット）
 
##作成した目的
ある企業の人事評価のため、勤怠管理を行う

##アプリケーションURL
GithubURL：https://github.com/palhina/Atte.git

##ほかのレポジトリ
今回はなし

##機能一覧
新規会員登録
ログイン機能
メールによる二段階認証
打刻（出勤開始・終了、休憩開始・終了）
日付毎の(ユーザー全員の)勤怠履歴表示
ユーザー一覧表示
ユーザー毎の勤怠履歴表示

##使用技術(実行環境)
Docker version 24.0.5
Laravel 8.83.27
Nginx 1.21.1
MySQL 8.0.26
PHP 8.1.2
Composer 2.2.6
MailHog

##テーブル設計
 
##ER図
 

##環境構築
###インストール手順
１．	プロジェクトを保存したいディレクトリに移動し、その後Githubからリポジトリをクローンします：
$git clone https://github.com/palhina/Atte.git
　　その後リポジトリのディレクトリに移動します：
$cd Atte
２．Dockerを使用し、アプリケーションを起動します：
	$docker-compose up -d --build
３．Laravelのパッケージをインストールするために、phpコンテナ内にログインします：
	$ docker-compose exec php bash
４．コンテナ内でComposerをインストールします：
	＄composer install
５．”.env”ファイルを作成し、データベース名、ユーザ名、パスワードなどの必要な環境変数を設定します：
	$cp .env.example .env
その後“.env”ファイルを編集し、必要な設定を追加、編集します。
６．アプリケーションキーを作成します：
$php artisan key:generate
７．	データベースのマイグレーションを実行します：
$php artisan migrate

・アプリケーションはデフォルトで http://localhost:8000 でアクセスできます。
・MySQLはデフォルトでhttp://localhost:8080でアクセスできます。
・２段階認証において、テストメールの確認にMailHogを使用しています。デフォルトでhttp://localhost:8025でアクセスできます。
・エラーが発生する場合、$ sudo chmod -R 777 *コマンドにて権限変更を行ってみてください。

##追記
IAMユーザー：
・Ami（権限：administration）
