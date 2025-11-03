# Alpine Linux 3.6をベースイメージとして使用（軽量なLinuxディストリビューション）
FROM alpine:3.6

# Nginxをインストール（--no-cacheでキャッシュを残さず、イメージサイズを最小化）
RUN apk update && apk add --no-cache nginx

# Nginxの実行時データ（PIDファイルなど）を保存するディレクトリを作成
# /runはFHS（Filesystem Hierarchy Standard）で定義された標準ディレクトリ
RUN mkdir -p /run/nginx

# Nginxをフォアグラウンドで起動（daemon off;でコンテナが終了しないようにする）
# daemon on;の場合、Nginxはバックグラウンドで動作し、コンテナがすぐに終了してしまう
CMD nginx -g "daemon off;"
