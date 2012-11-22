<?= form::open(); ?>
    <?= form::input('event', $event, array('size' => 100));?>
    <?= form::submit('submit', 'Zapisz'); ?>
<?= form::close(); ?>
