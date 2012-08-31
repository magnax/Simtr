<?= form::open(); ?>
    <?= form::label('email', 'Your e-mail address:'); ?>
    <?= form::input('email'); ?>
    <?= form::submit('send', 'Send new password'); ?>
<?= form::close(); ?>
