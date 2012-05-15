Wybierz dane do załadowania:<br />
<form action="<?php echo url::site('admin/fixtures/load'); ?>" method="POST">
    <?php foreach ($redis_keys as $key): ?>
        <input name="<?php echo $key; ?>" type="checkbox"><?php echo $key; ?><br />
    <?php endforeach; ?>
    <input type="submit" value="Załaduj">
</form>