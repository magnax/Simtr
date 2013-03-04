<?= Form::open(); ?>
    <?= Form::label('email', 'Twój adres e-mail:'); ?>
    <?= Form::input('email'); ?>
    <?= Form::submit('send', 'Wyślij mi nowe hasło'); ?>
<?= Form::close(); ?>
