<?php

function connectDatabase()
{
    $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');
    if (!$link) {
        echo 'Error: データベースに接続できません' . PHP_EOL;
        echo 'Debugging error:' . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    echo 'Success: データベースに接続できました' . PHP_EOL;
    return $link;
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

function closeDatabase($link)
{
    mysqli_close($link);
}
