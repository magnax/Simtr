<?= form::open(); ?>
    <?= form::input('project', $project, array('size' => 150));?>
    <?= form::submit('submit', 'Zapisz projekt'); ?>
<?= form::close(); ?>
