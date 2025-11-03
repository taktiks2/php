## Docker Compose

このプロジェクトでは、Nginx + PHP + MySQLの環境をDocker Composeで管理しています。

### 環境構成

```
services:
  web (my-php)    - PHPアプリケーション (ポート: 5173)
  nginx (my-nginx) - Webサーバー (ポート: 81)
  mysql (my-mysql) - データベース (ポート: 3306)
```

### 基本コマンド

#### コンテナの起動

```bash
docker compose up -d
```

- `-d` (detached): バックグラウンドで起動

#### コンテナの停止

```bash
docker compose down
```

- コンテナを停止して削除
- ボリュームは削除されないため、データは保持される

#### コンテナの状態確認

```bash
docker compose ps
```

#### ログの確認

```bash
# すべてのサービスのログを表示
docker compose logs

# 特定のサービスのログを表示
docker compose logs mysql
docker compose logs nginx
docker compose logs web

# リアルタイムでログを表示（-f: follow）
docker compose logs -f

# 最新20行のみ表示
docker compose logs --tail 20
```

#### コンテナの再起動

```bash
# すべてのサービスを再起動
docker compose restart

# 特定のサービスのみ再起動
docker compose restart mysql
```

#### コンテナ内でコマンドを実行

```bash
# PHPコンテナに接続（サービス名を指定）
docker compose exec web /bin/bash

# MySQLコンテナに接続
docker compose exec mysql /bin/bash

# MySQLに接続してクエリを実行
docker compose exec mysql mysql -u mysql -pmysql
```

**重要**: `docker compose exec`では**サービス名**を使用します（`web`, `mysql`, `nginx`）

#### コンテナ名で接続する場合

```bash
# コンテナ名を指定する場合はdocker execを使用
docker exec -it my-php /bin/bash
docker exec -it my-mysql /bin/bash
docker exec -it my-nginx /bin/bash
```

**違い**:
- `docker compose exec` → サービス名（`web`, `mysql`, `nginx`）を使用、`-it`は省略可能
- `docker exec` → コンテナ名（`my-php`, `my-mysql`, `my-nginx`）を使用、`-it`が必須

### ディレクトリ構成

```
docker-config/
├── nginx/
│   ├── Dockerfile
│   └── default.conf
├── php/
│   └── Dockerfile
└── mysql/
    ├── data/      # MySQLデータディレクトリ（gitignore推奨）
    ├── conf/      # MySQL設定ファイル（my.cnfなど）
    └── initdb/    # 初期化SQLスクリプト（.sqlファイルなど）
```

### MySQL初期化について

MySQLコンテナは初回起動時に以下を実行します：

1. `/var/lib/mysql`（dataディレクトリ）が空の場合、データベースを初期化
2. `/docker-entrypoint-initdb.d`（initdbディレクトリ）内の`.sql`ファイルを自動実行

**注意**: データディレクトリには設定ファイルやSQLファイルを配置しないこと

### トラブルシューティング

#### MySQLが起動しない場合

```bash
# ログを確認
docker compose logs mysql

# データディレクトリをクリーンアップして再起動
docker compose down
rm -rf docker-config/mysql/data/*
docker compose up -d
```

#### コンテナが起動しているか確認

```bash
docker compose ps

# 詳細な状態を確認
docker compose ps -a
```

#### ポートの競合エラー

```bash
# 既に使用されているポートを確認
lsof -i :3306  # MySQL
lsof -i :81    # Nginx
lsof -i :5173  # PHP
```

---

## Docker

### nginxディレクトリのDockerfileをビルド

docker build -t <image-tag-name>:latest ./nginx

または、nginxディレクトリに移動してから:

cd nginx
docker build -t <image-tag-name>:latest .

オプションの説明

- -t (--tag) - イメージに名前とタグを付ける
  - イメージ名:タグ の形式（例: my-nginx:latest, my-nginx:1.0）
  - タグを省略すると自動的に :latest が付く
- . または ./nginx - ビルドコンテキスト（Dockerfileがあるディレクトリ）

### ビルドしたイメージを確認
docker images

### イメージを実行してテスト
docker run -d -p 8080:80 <image-tag-name>:latest

#### オプションの説明

**`-d` (detached mode: デタッチドモード)**
- コンテナをバックグラウンドで実行
- コンテナIDを表示して、すぐにシェルに制御が戻る
- `-d` なしの場合、ターミナルがコンテナの出力に占有される

**`-p` (port mapping: ポートマッピング)**
- 形式: `-p ホスト側ポート:コンテナ側ポート`
- 例: `-p 8080:80` の場合
  - `8080` = ホストマシン（あなたのPC）のポート
  - `80` = コンテナ内部のポート
  - `http://localhost:8080` にアクセスすると、コンテナ内の80番ポートに転送される

### コンテナが動作しているか確認
docker ps

### 起動中のコンテナ内でシェルを起動

```bash
# Alpine Linuxベースの場合（bashが入っていないのでshを使う）
docker exec -it <コンテナID or コンテナ名> sh

# bashがインストールされている場合
docker exec -it <コンテナID or コンテナ名> bash
```

#### オプションの説明

**`-i` (interactive: インタラクティブ)**
- 標準入力を開いたままにする
- コマンドを入力できるようにする

**`-t` (tty: 疑似ターミナル)**
- 疑似ターミナルを割り当てる
- プロンプトやカラー表示などを有効にする

**`-it` を組み合わせると**
- ターミナルとして普通にシェル操作ができる

#### シェルから抜ける方法

```bash
exit
```

または `Ctrl + D`

---

## Nginxについて（TypeScript開発者向け）

### なぜTypeScript開発者はNginxに馴染みがないのか

#### 1. Node.jsは単体でWebサーバーとして動作できる

**TypeScript/Node.js開発の場合**
```typescript
// Express.jsの例
import express from 'express';
const app = express();

app.listen(3000); // Node.js自体がHTTPサーバー
```
- Node.jsが直接HTTPリクエストを処理できる
- Webサーバー（Nginx/Apache）が不要

**PHPの場合**
```php
<?php
// PHPファイル単体ではHTTPリクエストを受け付けられない
echo "Hello World";
?>
```
- PHPはプログラミング言語のみ
- HTTPリクエストを受け付けるには**Nginx/Apacheが必須**
- PHP-FPMはPHP実行環境だが、Webサーバーではない

#### 2. 開発サーバーが組み込まれている

TypeScript開発では、フレームワークに開発サーバーが含まれています:
```bash
npm run dev  # Next.js、Vite、Create React Appなど
```
これらは全てNode.jsベースのサーバーなので、Nginxは不要です。

#### 3. PaaSへのデプロイが主流

TypeScript/Node.jsアプリは多くの場合:
- **Vercel** (Next.js)
- **Netlify** (静的サイト)
- **Heroku** (Node.jsアプリ)
- **AWS Lambda** (サーバーレス)

これらのプラットフォームにデプロイするため、**Nginxの設定は裏で自動化されており、開発者には見えない**。

#### 4. 本番環境でもNginxに触れない

- **PHP開発の場合**: 必ずNginx/Apacheの設定が必要
- **TypeScript/Node.js開発の場合**: Node.jsアプリを直接起動（Nginxはインフラエンジニアがリバースプロキシとして配置することはある）

### TypeScript開発者がNginxに触れるケース

以下のような場合に初めて必要になります:

1. **リバースプロキシ**として使う場合
   ```
   [Nginx:80] → [Node.js:3000]
   ```

2. **複数アプリケーションを1つのサーバーで動かす**場合
   ```
   /api     → Node.jsアプリ
   /admin   → 別のNode.jsアプリ
   /static  → 静的ファイル
   ```

3. **SSL終端**やロードバランシング

4. **オンプレミスやVPSで本番環境を構築**する場合

### Go、Rustの場合も同じ

**Go (Golang)**
```go
package main

import (
    "net/http"
)

func main() {
    http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
        w.Write([]byte("Hello World"))
    })
    http.ListenAndServe(":8080", nil) // 標準ライブラリでHTTPサーバー
}
```
- 標準ライブラリ（`net/http`）でHTTPサーバーを持つ
- コンパイル済みバイナリとして動作
- Nginx不要で本番環境でも直接起動することが多い

**Rust**
```rust
use actix_web::{web, App, HttpServer};

#[actix_web::main]
async fn main() -> std::io::Result<()> {
    HttpServer::new(|| {
        App::new()
            .route("/", web::get().to(|| async { "Hello World" }))
    })
    .bind("127.0.0.1:8080")?  // Actix-webがHTTPサーバー
    .run()
    .await
}
```
- Actix-web、Rocket、Axumなどのフレームワークが組み込みHTTPサーバーを持つ
- コンパイル済みバイナリとして動作
- 高速で安全なため、本番環境でも直接起動することが多い

### 言語別のWebサーバー依存度

| 言語/環境 | 単体で動作 | Nginx/Apache必須 | 理由 |
|----------|----------|----------------|------|
| **PHP** | ❌ | ✅ | FastCGI方式で動作、HTTPサーバーではない |
| **Node.js** | ✅ | ❌ | 組み込みHTTPサーバー、独立プロセス |
| **Go** | ✅ | ❌ | 標準ライブラリでHTTPサーバー、コンパイル済みバイナリ |
| **Rust** | ✅ | ❌ | フレームワークに組み込みHTTPサーバー、コンパイル済みバイナリ |
| **Python** | ✅ | △ | Gunicorn/uWSGI等で動作、本番ではNginxをリバースプロキシとして使うことが多い |
| **Ruby** | ✅ | △ | Puma/Unicorn等で動作、本番ではNginxをリバースプロキシとして使うことが多い |

### なぜ同じスクリプト言語なのにPHPだけNginxが必須なのか？

#### アプリケーションサーバーの有無

**Ruby/Pythonの場合**
```
[ブラウザ] → [アプリケーションサーバー (Puma/Gunicorn)] → [アプリ]
                    ↑ これがHTTPリクエストを受け付ける
```
- **Puma** (Ruby)、**Gunicorn/uWSGI** (Python) がHTTPサーバーとして動作
- 独立したプロセスとして起動し、HTTPリクエストを直接処理できる
- Nginxは任意（パフォーマンスやSSLのために使うことが多い）

**PHPの場合**
```
[ブラウザ] → [必ずNginx/Apacheが必要] → [PHP-FPM] → [アプリ]
```
- **PHP-FPMはHTTPサーバーではない**
- FastCGIプロトコルでしか通信できない（ブラウザと直接通信不可）
- 必ずNginx/Apacheが必要

#### 歴史的背景と設計思想

**PHP（1995年〜）**
- 元々Apache専用のモジュールとして設計された
- 「Webサーバーの拡張機能」という位置付け
- 単体で動作する設計ではなかった
- PHP-FPMが登場した後も、この思想は変わっていない

**Python/Ruby（1990年代〜）**
- 汎用プログラミング言語として設計
- Web開発は後から追加された機能
- **WSGI**(Python)、**Rack**(Ruby)という標準インターフェースを定義
- アプリケーションサーバーという概念を持つ

#### 技術的な違い

| | PHP | Ruby/Python |
|---------|-----|-------------|
| **実行モデル** | リクエスト毎にプロセス/スレッド起動 | 常駐プロセス（アプリケーションサーバー） |
| **HTTPサーバー** | 持たない（FastCGIのみ） | 持つ（Puma/Gunicorn等） |
| **起動方法** | Nginx/Apacheから呼び出される | 独立したサービスとして起動 |
| **プロトコル** | FastCGI | HTTP（直接ブラウザと通信可能） |

#### なぜRuby/Pythonは本番環境でNginxを併用するのか？

Nginxなしでも動作しますが、以下の理由で併用されることが多いです：

1. **静的ファイルの配信** - Nginxの方が高速
2. **SSL終端** - Nginxで証明書管理
3. **ロードバランシング** - 複数のアプリケーションサーバーへ振り分け
4. **リバースプロキシ** - セキュリティ、キャッシング

**重要な違い**
- Ruby/Python: **パフォーマンス向上のため**にNginxを使う（任意）
- PHP: **動作に必須**（必須）

### FastCGIとは何か？（TypeScript開発者向け）

#### FastCGI = 通信プロトコルの一種

Webサーバーとアプリケーション（PHPなど）が会話するための「言語」です。

#### 通信プロトコルの比較

| プロトコル | 用途 | 例 |
|-----------|------|-----|
| **HTTP** | ブラウザ ←→ Webサーバー | あなたのブラウザとNginxの通信 |
| **FastCGI** | Webサーバー ←→ アプリケーション | NginxとPHP-FPMの通信 |
| **WebSocket** | ブラウザ ←→ サーバー（双方向） | リアルタイムチャット |

#### Node.js/Expressとの比較

**Node.js/Express の場合（FastCGI不要）**
```typescript
// すべて同じプロセス内で完結
const express = require('express');
const app = express();

app.get('/', (req, res) => {  // ← HTTPリクエストを直接受け取る
  res.send('Hello');
});

app.listen(3000);  // ← HTTPサーバーとして動作
```

**PHP + Nginx の場合（FastCGIが必要）**
```
1. ブラウザ → [HTTP] → Nginx
2. Nginx → [FastCGI] → PHP-FPM  ← ここで別のプロトコルに変換
3. PHP-FPM → PHPコード実行
4. PHP-FPM → [FastCGI] → Nginx
5. Nginx → [HTTP] → ブラウザ
```

**なぜプロトコルを変換するのか？**
- NginxとPHP-FPMは**別々のプロセス**（別々のプログラム）
- 異なるプロセス間で通信するには、共通のプロトコルが必要
- FastCGIはWebサーバーとアプリケーション間の通信用に最適化されている

#### 実際の設定例（nginx/default.conf）

```nginx
location ~ \.php$ {
  # PHP-FPMへのリクエスト転送先（webコンテナの9000番ポート）
  fastcgi_pass web:9000;  ← ここでFastCGIプロトコルを使って通信

  # FastCGIの基本パラメータを読み込む
  include fastcgi_params;

  # 実行するPHPスクリプトのフルパスを指定
  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

#### Node.jsで例えると

もしNode.jsがPHPと同じアーキテクチャだったら:
```
[Nginx] ←HTTP→ [ブラウザ]
   ↓
  FastCGI通信（web:9000）
   ↓
[Node.js-FPM] ← こんなものは存在しない！
   ↓
[Node.jsアプリ実行]
```

でも実際は:
```
[Node.js + Express] ←HTTP→ [ブラウザ]
     ↑
   全部ここで完結（FastCGI不要）
```

#### FastCGIとHTTPの違い

| | HTTP | FastCGI |
|---|------|---------|
| **目的** | ブラウザとサーバーの通信 | Webサーバーとアプリケーションの通信 |
| **最適化** | 汎用的 | サーバー間通信に特化（軽量・高速） |
| **ヘッダー** | 多機能（Cookie、認証など） | 最小限（実行に必要な情報のみ） |
| **例** | `GET /index.html HTTP/1.1` | バイナリ形式で効率的に送信 |

#### PHP-FPMの「FPM」とは

**FPM** = **FastCGI Process Manager**

- **FastCGI** - 高速なCGI（Common Gateway Interface）プロトコル
- **Process Manager** - プロセス管理ツール

つまり、**FastCGIプロトコルを使ってPHPスクリプトを実行するプロセスを管理するソフトウェア**です。

**PHP-FPMが行うこと:**
1. **プロセスプールの管理** - 複数のPHPプロセスを事前に起動しておく
2. **パフォーマンスの最適化** - プロセスの再利用により、毎回起動する必要がない
3. **FastCGI通信** - NginxからのFastCGIリクエストを受け取り、PHPを実行して結果を返す

**従来のCGI vs FastCGI:**

```
従来のCGI（遅い）:
リクエスト → PHPプロセス起動 → 実行 → 終了
リクエスト → PHPプロセス起動 → 実行 → 終了  ← 毎回起動

FastCGI / PHP-FPM（速い）:
起動時: PHPプロセスを複数起動して待機
リクエスト → 待機中のプロセスに割り当て → 実行 → プロセスは再利用
リクエスト → 待機中のプロセスに割り当て → 実行 → プロセスは再利用
```

#### まとめ

- **FastCGI** = WebサーバーとPHPなどのアプリケーションを繋ぐ通信プロトコル
- **HTTPではない** = ブラウザとは直接通信できない
- **だからNginxが必要** = HTTPをFastCGIに変換してくれる
- **PHP-FPM** = FastCGIプロトコルでPHPを実行するプロセスマネージャー
- Node.js開発者は、これらがすべて1つのプロセス内で完結するため、FastCGIという概念に触れることがない

### まとめ

- **PHP以外のモダン言語**（Node.js、Go、Rust等）は、**独自のHTTPサーバーを持つ**ためNginx不要
- TypeScript/Node.js開発者がNginxに馴染みがないのは、**エコシステムが開発からデプロイまでを抽象化しているから**
- PHP開発では常にNginx/Apacheが必須なので、自然と触れる機会が多い
- リバースプロキシ、ロードバランシング、SSL終端などの用途では、どの言語でもNginxを使うことがある

### 参考資料

https://zenn.dev/fire_arlo/articles/laravel-docker-setup-without-sail
