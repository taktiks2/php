<?php

require_once __DIR__ . '/mysql.php';

const TITLE = '書籍名: ';
const AUTHOR = '著者名: ';
const STATUS = '読書状況: ';
const SCORE = '評価: ';
const SUMMARY = '感想: ';

$link = connectDatabase();

function validate($review): array
{
    $errors = [];

    // 書籍名が正しく入力されているかチェック
    if (!mb_strlen($review['title'])) {
        $errors['title'] = '書籍名を入力してください';
    } elseif (mb_strlen($review['title']) > 255) {
        $errors['title'] = '書籍名は255文字以内で入力してください';
    }

    // 著者名が正しく入力されているかチェック
    if (!mb_strlen($review['author'])) {
        $errors['author'] = '著者名を入力してください';
    } elseif (mb_strlen($review['author']) > 100) {
        $errors['author'] = '著者名は100文字以内で入力してください';
    }

    // 読書状況が正しく入力されているかチェック
    if (!in_array($review['status'], ['未読', '読んでる', '読了'], true)) {
        $errors['status'] = '読書状況は「未読」「読んでる」「読了」のいずれかを入力してください';
    }

    // 評価が正しく入力されているかチェック
    if ($review['score'] < 1 || $review['score'] > 5) {
        $errors['score'] = '評価は1〜5の整数を入力してください';
    }

    // 感想が正しく入力されているかチェック
    if (!mb_strlen($review['summary'])) {
        $errors['summary'] = '感想を入力してください';
    } elseif (mb_strlen($review['summary']) > 1000) {
        $errors['summary'] = '感想は1,000文字以内で入力してください';
    }

    return $errors;
}

function createReview($link)
{
    echo '読書ログを登録してください' . PHP_EOL;

    $reviews = [];

    echo TITLE;
    $reviews['title'] = trim(fgets(STDIN));

    echo AUTHOR;
    $reviews['author'] = trim(fgets(STDIN));

    echo STATUS;
    $reviews['status'] = trim(fgets(STDIN));

    echo SCORE;
    $reviews['score'] = trim(fgets(STDIN));

    echo SUMMARY;
    $reviews['summary'] = trim(fgets(STDIN));

    $validated = validate($reviews);

    if (count($validated) > 0) {
        foreach ($validated as $error) {
            echo $error . PHP_EOL;
        }
        return;
    }

    addReview($link, $reviews);

    echo '登録が完了しました' . PHP_EOL . PHP_EOL;
}

function listReviews($link)
{
    echo '読書ログを表示します' . PHP_EOL;

    $reviews = getAllReviews($link);

    foreach ($reviews as $review) {
        echo TITLE . $review['title'] . PHP_EOL;
        echo AUTHOR . $review['author'] . PHP_EOL;
        echo STATUS . $review['status'] . PHP_EOL;
        echo SCORE . $review['score'] . PHP_EOL;
        echo SUMMARY . $review['summary'] . PHP_EOL;
        echo '----------------' . PHP_EOL;
    }
}

function finish($link)
{
    echo 'アプリの終了';
    closeDatabase($link);
}

while (true) {
    echo '1. 読書ログを登録' . PHP_EOL;
    echo '2. 読書ログを表示' . PHP_EOL;
    echo '9. アプリケーションを終了' . PHP_EOL;
    echo '番号を選択してください(1,2,9): ';
    $num = trim(fgets(STDIN));
    if ($num === '1') {
        createReview($link);
    } elseif ($num === '2') {
        listReviews($link);
    } elseif ($num === '9') {
        finish($link);
        break;
    } else {
        echo '無効な入力です';
    }
}
