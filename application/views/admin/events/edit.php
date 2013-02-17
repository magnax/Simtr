<?= form::open(); ?>
    <?= form::input('event', $event, array('size' => 150));?>
    <?= form::submit('submit', 'Zapisz'); ?>
<?= form::close(); ?>
