# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

読書ログサービスを作成するための学習プロジェクト。
自分が読んだ本のログ（書籍名、著者名、読書状況、評価、感想）を登録し、表示できるサービスです。

## 開発環境

- **環境**: Docker Compose (app + db)
- **PHP**: 7.4-apache
- **MySQL**: 5.5.62
- **ポート**:
  - アプリケーション: `50080:80`
  - データベース: `53306:3306`
- **ソースコード**: `./src` ディレクトリ（コンテナ内 `/var/www/html` にマウント）

### データベース接続情報

- データベース名: `book_log`
- ユーザー名: `book_log`
- パスワード: `pass`
- ルートパスワード: `pass`

## よく使うコマンド

### Docker 環境の起動・停止

```bash
# イメージのビルド
docker-compose build

# コンテナの起動
docker-compose up -d

# コンテナの状態確認
docker-compose ps

# コンテナの停止・削除
docker-compose down
```

### コンテナ内でのコマンド実行

```bash
# PHPバージョン確認
docker-compose exec app php -v

# bash操作
docker-compose exec app /bin/bash

# MySQLクライアント（コンテナ内から）
mysql -u book_log -ppass -h db book_log
```

### ログ確認

```bash
# アプリケーションのログ
docker-compose logs app

# データベースのログ
docker-compose logs db
```

## 開発ステップ

このプロジェクトは段階的に開発を進める構成になっています：

1. **テキスト版アプリケーション（CLI）**
   - 読書ログの表示
   - 読書ログの登録
   - メニュー化
   - 複数ログ対応

2. **データベース対応**
   - テーブル作成
   - DB登録・表示機能

3. **Webアプリケーション化**
   - HTML登録画面
   - HTML一覧画面

4. **デプロイ（Heroku）**
   - Herokuアカウント作成
   - Heroku上での動作

## アーキテクチャノート

- `/src`: アプリケーションのソースコード（現在は空）
- `/docker/app/`: PHP/Apache環境設定
- `/docker/db/`: MySQL環境設定とデータ永続化
- ルートの `Dockerfile`: Herokuデプロイ用
- `docker-compose.yml`: ローカル開発環境用

## トラブルシューティング

### コンテナが起動しない場合

1. ログを確認: `docker-compose logs [app|db]`
2. Dockerfile周りを変更した場合は `docker-compose down` → `docker-compose build` → `docker-compose up -d`

### dbコンテナがInnoDBエラーで起動しない場合

1. MySQLデータを削除: `rm -rf docker/db/mysql_data`
2. ボリュームを含めて削除: `docker system prune --volumes`
3. 必要に応じて `docker-compose.yml` から `- ./docker/db/mysql_data:/var/lib/mysql` を削除

### ポート重複で起動しない場合

- PC再起動
- `docker-compose.yml` の `ports` の左側の番号を変更

### ディスク容量が逼迫した場合

`docker system prune` で不要なリソースを削除
