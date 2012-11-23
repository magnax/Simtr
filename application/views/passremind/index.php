<?= form::open(); ?>
    <?= form::label('email', 'Twój adres e-mail:'); ?>
    <?= form::input('email'); ?>
    <?= form::submit('send', 'Wyślij mi nowe hasło'); ?>
<?= form::close(); ?>
