<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

function dbConnect()
{
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbName = $_ENV['DB_NAME'];

    $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    if (!$link) {
        echo 'Error: データベースに接続できません' . PHP_EOL;
        echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    echo 'Success: データベースに接続できました' . PHP_EOL;
    return $link;
}

function dropTable($link)
{
    $sql = 'drop table if exists reviews';
    $res = mysqli_query($link, $sql);
    if ($res) {
        echo 'テーブルの削除に成功しました' . PHP_EOL;
    } else {
        echo 'Error: テーブルの削除に失敗しました' . PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL;
    }
}

function createTable($link)
{
    $sql = <<<EOT
create table reviews (
  id integer auto_increment not null primary key,
  title varchar(255),
  author varchar(100),
  status varchar(10),
  score integer,
  summary varchar(1000),
  created_at timestamp not null default current_timestamp
) default character set=utf8mb4
EOT;
    $res = mysqli_query($link, $sql);
    if ($res) {
        echo 'テーブルの作成に成功しました' . PHP_EOL;
    } else {
        echo 'Error: テーブルの作成に失敗しました' . PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL;
    }
}

$link = dbConnect();
dropTable($link);
createTable($link);
mysqli_close($link);
