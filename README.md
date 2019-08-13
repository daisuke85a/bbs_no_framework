# 概要
PHP+MySQLで作られた掲示板です  
フレームワークを使わずフルスクラッチで開発しています  

# 目的
案件獲得用のポートフォリオ  
PHPを中心としたサーバーサイド側の案件を想定

# ウリ
フルスクラッチで開発している。フレームワークに用意されていない特殊な処理も開発できます。  
簡易的なMVCフレームワークを独自に構築している(Laravelを参考にしている)  
オブジェクト指向で書かれている
循環参照をしていない  
セキュリティ対策をしている(CSRF,XSS,SQLインジェクション,不正なファイルアップロード)  
PHP Unitによる自動試験に対応している  
（予定）CI/DIに対応する  

# 制約
デザインは貧弱  
あくまでサーバーサイドのスキルをアピールしている

# 仕様
ログイン、テキスト投稿、画像投稿、本人による投稿の削除ができる掲示板を作成する  
ユーザのメールアドレスは一意である  
ユーザは140文字以内の任意のテキストを投稿できる  
ユーザが入力したテキストや画像の他、投稿日時を表示する  
各投稿には、投稿時と同様の条件でコメントがつけられる  

# 検証環境
PHP7.3.1  
MySQL8.0.14  
Google Chrome最新バージョン(2019/08/13時点)
apache

# 利用方法
書き途中  
1. apacheのドキュメントルートへ移動する  
2. $ git clone https://github.com/daisuke85a/bbs_no_framework.git
3. $ mysql -u root -p < sql.txt を実行する
4. 