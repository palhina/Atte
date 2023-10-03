# 勤怠管理アプリ Atte

## アプリケーション名  
**Atte（アット）**
### 新規会員登録ページ  
Fortifyを使用しています。  
正しく情報を入力し「会員登録」ボタンを押下すると、二段階認証ページに遷移します。  
バリデーションルールは以下の通りです。(名前、メールアドレス、パスワードはいずれも必須項目)  
+ 名前：191文字以内、文字列型。  
+ メールアドレス：191文字以内、文字列型（メールアドレス形式）。既に登録されているメールアドレスとの重複不可。  
+ パスワード：8文字以上191文字以内、文字列型。パスワードと確認用パスワードは同一のものが入力されていること。  
![新規会員登録](https://github.com/palhina/Atte/assets/129643430/2a044921-43a1-42f5-b5ed-d331e54d179c)  
### ログインページ  
Fortifyを使用しています。  
登録した情報を正しく入力し「ログイン」ボタンを押下すると、打刻ページへ遷移しナビゲーションメニューが表示されます。  
前回ログアウト時の勤務・休憩の状況により、表示される打刻ページは異なります。
![ログイン画面](https://github.com/palhina/Atte/assets/129643430/5ecb0082-032e-4f3c-b378-f8701497c9b5)
### 二段階認証  
Fortifyを使用しています。  
新規会員登録または初回ログイン時に表示され、同時に登録したメールアドレスに認証メールが送信されます。    
「メール再送信」ボタン押下で、認証メールが再度送信されます。  
![2段階認証](https://github.com/palhina/Atte/assets/129643430/3658d222-a353-447a-b58b-9fd997dd82f2)
### メール画面  
二段階認証時、登録されたメールアドレスに送信されるメール画面です。  
「メールアドレスの確認」ボタン押下で二段階認証は完了し、打刻ページに遷移しナビゲーションメニューが表示されます。  
![メール画面(2段階認証用)](https://github.com/palhina/Atte/assets/129643430/521c4bc9-f63f-4dc9-9659-2d2bed05f41c)  
### 打刻ページ(勤務開始)  
勤務開始前または最後に押したボタンが「勤務終了」であった場合、ログイン成功直後やナビゲーションメニュー「ホーム」押下で表示されるページです。  
 打刻ボタンは「勤務開始」のみ押すことが可能です。  
 「勤務開始」ボタン押下で打刻ページ(勤務終了、休憩開始)に遷移します。  
![打刻ページ(勤務開始)](https://github.com/palhina/Atte/assets/129643430/e14a609f-82ba-4ac5-9702-9e183b228e24)
### 打刻ページ(勤務終了、休憩開始)    
最後に押したボタンが「勤務開始」、または「休憩終了」だった場合、ログイン成功直後やナビゲーションメニュー「ホーム」押下で表示されるページです。  
打刻ボタンは「勤務終了」「休憩開始」のみ押すことが可能です。 
「勤務終了」ボタン押下で打刻ページ(勤務開始)に遷移します。    
「休憩開始」ボタン押下で打刻ページ(休憩終了)に遷移します。  
![打刻ページ(勤務終了・休憩開始)](https://github.com/palhina/Atte/assets/129643430/15c8645f-008d-4b1a-af80-0fe78ac21073)
### 打刻ページ(休憩終了)  
最後に押したボタンが「休憩開始」だった場合、ログイン成功直後やナビゲーションメニュー「ホーム」押下で表示されるページです。  
打刻ボタンは「休憩終了」のみ押すことが可能です。  
「休憩終了」ボタン押下で打刻ページ(勤務終了、休憩開始)に遷移します。  
![打刻ページ(休憩終了)](https://github.com/palhina/Atte/assets/129643430/359c0b62-8b06-4836-b705-ad93a4ec239c)  
### 日付一覧ページ  
ナビゲーションメニュー「日付一覧」押下で表示されます。  
ある特定の年月日に出勤したユーザー全員の勤怠情報が表示されます。  
年月日（デフォルトでは本日を表示）の両側にある「＜」「＞」ボタン押下で、それぞれ前日、翌日の勤怠情報に遷移します。  
5件ずつページネーションされます。  
![日付別勤怠ページ](https://github.com/palhina/Atte/assets/129643430/62ec2356-972b-4d9b-ab41-c67caacb10ee)　　
### ユーザー一覧ページ  
ナビゲーションメニュー「ユーザー一覧」押下で表示されます。  
登録されているユーザー一覧が表示されます。    
5件ずつページネーションされます。  
![ユーザー一覧](https://github.com/palhina/Atte/assets/129643430/a49917bf-0f80-4ce0-9e4e-0ab6c8b6f467)
### ユーザー別勤怠一覧ページ  
ナビゲーションメニュー「ユーザー別勤怠一覧」押下で表示されます。  
現在ログイン中のユーザーの勤怠一覧を表示します。  
5件ずつページネーションされます。  
![ユーザー別勤怠一覧](https://github.com/palhina/Atte/assets/129643430/8e2e45a0-369c-4c53-b0d1-909cbe94445e)

## 作成した目的  
ある企業の人事評価のため、勤怠管理を行う  

## アプリケーションURL  
GithubURL：https://github.com/palhina/Atte.git  

## ほかのレポジトリ  
今回はなし  

## 機能一覧  
新規会員登録  
ログイン機能  
メールによる二段階認証  
打刻（出勤開始・終了、休憩開始・終了）  
日付毎の(ユーザー全員の)勤怠履歴表示  
ユーザー一覧表示  
ユーザー毎の勤怠履歴表示    

## 使用技術(実行環境)  
Docker version 24.0.5  
Laravel 8.83.27  
認証機能はFortify使用  
Nginx 1.21.1  
MySQL 8.0.26  
PHP 8.1.2  
Composer 2.2.6  
MailHog  

## テーブル設計  
![テーブル仕様書](https://github.com/palhina/Atte/assets/129643430/a50519bd-6862-478f-aed5-b1362fcc23e0) 
 
## ER図  
![ER図](https://github.com/palhina/Atte/assets/129643430/0b810ea9-2b7e-49b2-96b7-6a49dc3bfec7)


## 環境構築  

* インストール手順

１．プロジェクトを保存したいディレクトリに移動し、その後Githubからリポジトリをクローンします：

        $git clone https://github.com/palhina/Atte.git
        
その後リポジトリのディレクトリに移動します：

        $cd Atte

２．Dockerを使用し、アプリケーションを起動します：
	
         $docker-compose up -d --build

３．Laravelのパッケージをインストールするために、phpコンテナ内にログインします：
	
          $ docker-compose exec php bash

４．コンテナ内でComposerをインストールします：
	
         $composer install

５．”.env”ファイルを作成し、データベース名、ユーザ名、パスワードなどの必要な環境変数を設定します：
	
         $cp .env.example .env

その後“.env”ファイルを編集し、必要な設定を追加、編集します。

６．アプリケーションキーを作成します：

        $php artisan key:generate

７．	データベースのマイグレーションを実行します：

        $php artisan migrate


* アプリケーションはデフォルトで http://localhost:8000 でアクセスできます。

* MySQLはデフォルトで http://localhost:8080 でアクセスできます。

* ２段階認証において、テストメールの確認にMailHogを使用しています。デフォルトで http://localhost:8025 でアクセスできます。

* エラーが発生する場合、$ sudo chmod -R 777 *コマンドにて権限変更を行ってみてください。  


## 追記  
**日付をまたいだ場合の挙動について**
* 24時時点で勤務中の場合  
  当日23:59:59に勤務終了し、翌日00:00:00に勤務開始したと記録されます。  
  （翌日に「休憩開始」または「勤務終了」ボタンを押した時点で動作） 
* 24時時点で休憩中の場合  
  当日23:59:59に休憩終了・勤務終了し、翌日00:00:00に勤務開始・休憩開始したと記録されます。  
  （翌日に「休憩終了」ボタンを押した時点で動作）    

**IAMユーザー**  

・Ami（権限：administration）
