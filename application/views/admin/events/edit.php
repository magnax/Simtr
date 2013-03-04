<?= Form::open(); ?>
    <?= Form::input('event', $event, array('size' => 150));?>
    <?= Form::submit('submit', 'Zapisz'); ?>
<?= Form::close(); ?>
