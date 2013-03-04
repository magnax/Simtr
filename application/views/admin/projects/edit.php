<?= Form::open(); ?>
    <?= Form::input('project', $project, array('size' => 150));?>
    <?= Form::submit('submit', 'Zapisz projekt'); ?>
<?= Form::close(); ?>
