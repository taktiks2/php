<?php

require_once __DIR__ . '/lib/mysqli.php';
require_once __DIR__ . '/lib/escape.php';

$link = dbConnect();

$reviews = getAllReviews($link);

$title = '読書ログの一覧';

$content = __DIR__ . '/views/index.php';
include __DIR__ . '/views/layout.php';
