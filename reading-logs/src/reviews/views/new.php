<form action="create.php" method="POST">
    <?php if (count($errors)) : ?>
    <ul style="color: red;">
        <?php foreach ($errors as $error) : ?>
        <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div>
    <label for="title">タイトル</label>
    <input type="text" id="title" name="title" />
    </div>
    <div>
    <label for="author">著者名</label>
    <input type="text" id="author" name="author" />
    </div>
    <div>
        <label>読書状況</label>
        <div>
            <div>
                <input type="radio" name="status" id="status1" value="未読" checked>
                <label for="status1">未読</label>
            </div>
            <div>
                <input type="radio" name="status" id="status2" value="読んでる">
                <label for="status2">読んでる</label>
            </div>
            <div>
                <input class="form-check-input" type="radio" name="status" id="status3" value="読了">
                <label for="status3">読了</label>
            </div>
        </div>
    </div>
    <div>
        <label for="score">評価（5点満点の整数）</label>
        <input type="number" name="score" id="score">
    </div>
    <div>
        <label for="summary">感想</label>
        <textarea type="text" name="summary" id="summary" rows="10"></textarea>
    </div>
    <button type="submit">登録する</button>
</form>
