<a href="new.php">会社情報の登録</a>

<main>
    <?php if (count($reviews) > 0) : ?>
        <?php foreach ($reviews as $review) : ?>
            <section>
                <h2>タイトル: <?php echo escape($review['title']) ?></h2>
                <div>著者: <?php echo escape($review['author']) ?></div>
                <div>ステータス: <?php echo escape($review['status']) ?></div>
                <div>スコア: <?php echo escape($review['score']) ?></div>
                <div>サマリー: <?php echo escape($review['summary']) ?></div>
            </section>
        <?php endforeach; ?>
    <?php else : ?>
        <div>まだ登録されていませｎ</div>
    <?php endif ; ?>
</main>
