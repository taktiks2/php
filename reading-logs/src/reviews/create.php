<?php

require_once __DIR__ . '/lib/mysqli.php';

function validate($review)
{
    $errors = [];

    if (!strlen($review['title'])) {
        $errors[] = 'タイトルを入力してください';
    }

    if (!strlen($review['author'])) {
        $errors[] = '著者名を入力してください';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = [
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'status' => $_POST['status'],
        'score' => $_POST['score'],
        'summary' => $_POST['summary'],
    ];

    $errors = validate($review);

    if (!count($errors)) {
        $link = dbConnect();

        addReview($link, $review);

        closeConnect($link);

        header("Location: index.php");
    }
}

include __DIR__ . '/views/new.php';
