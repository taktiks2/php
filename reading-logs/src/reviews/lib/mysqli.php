<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

function dbConnect()
{
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();

    $dbHost = $_ENV['DB_HOST'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];
    $dbName = $_ENV['DB_NAME'];

    $link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    if (!$link) {
        error_log('Error: fail to connect database');
        error_log('Debugging Error: ' . mysqli_error($link));
        exit;
    }
    return $link;
}

function dropTable($link)
{
    $sql = 'drop table if exists reviews';
    $res = mysqli_query($link, $sql);
    if (!$res) {
        error_log('Error: fail to create review');
        error_log('Debugging Error: ' . mysqli_error($link));
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

function addReview($link, $reviews)
{
    $sql = <<<EOT
INSERT INTO reviews (title, author, status, score, summary)
VALUES ("{$reviews['title']}", "{$reviews['author']}", "{$reviews['status']}", "{$reviews['score']}", "{$reviews['summary']}")
EOT;

    $res = mysqli_query($link, $sql);
    if ($res) {
        echo 'データの追加に成功しました' . PHP_EOL;
    } else {
        echo 'ERrOR: データの追加に失敗しました' . PHP_EOL;
        echo 'Debugging error: ' . mysqli_error($link) . PHP_EOL;
    }
}

function getAllReviews($link)
{
    $sql = <<<EOT
SELECT * FROM reviews
EOT;
    $res = mysqli_query($link, $sql);

    $reviews = [];
    while ($review = mysqli_fetch_assoc($res)) {
        $reviews[] = [
            'title' => $review['title'],
            'author' => $review['author'],
            'status' => $review['status'],
            'score' => $review['score'],
            'summary' => $review['summary'],
        ];
    }
    mysqli_free_result($res);

    return $reviews;
}

function closeConnect($link)
{
    mysqli_close($link);
}
