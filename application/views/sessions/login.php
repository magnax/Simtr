<div class="title_bar">
    Logowanie do gry
</div>
<?php if (isset($errors['failed'])): ?>
    <div class="error">Błąd: <?php echo Arr::path($errors, 'failed'); ?></div>
<?php endif; ?>
<div class="main_area">
<?php echo Form::open(); ?>
    <ul>
        <li>
            <?php echo Form::label('email', 'E-mail: '); ?>
            <?php echo Form::input('email', null, array('placeholder'=>'Twój e-mail')); ?><br />
        </li>
        <li>
            <?php echo Form::label('password', 'Hasło: '); ?>
            <?php echo Form::password('password', null, array('placeholder'=>'Twoje hasło')); ?><br />
        </li>
        <li>
            <?php echo Form::label('remember', 'Zapamiętaj mnie'); ?>
            <?php echo Form::checkbox('remember'); ?>
        </li>
        <li>
            <?php echo Form::submit('login', 'Zaloguj'); ?><br />
        </li>
    </ul>

<?php echo Form::close(); ?>
Nie masz jeszcze konta? <?php echo HTML::anchor('register', 'Zarejestruj się!'); ?><br />    
<?php echo HTML::anchor('passremind', 'Przypomnij mi hasło'); ?>
    <br /><br /><p><b>WAŻNE!!</b> Jeśli coś nie działa jak powinno to proszę to zgłaszać najlepiej poprzez forum <a href="http://simtr-forum.magnax.pl" target="_new">http://simtr-forum.magnax.pl</a>. Zachęcam do rejestracji, czytania i pisania. Najbardziej aktualne informacje o postępach w pracach nad grą znajdują się w <a href="http://simtr-forum.magnax.pl/viewtopic.php?f=2&t=6&p=259#p259" target="_new">TYM WĄTKU</a>. Można też kontaktować się mailowo: magnax@gmail.com</p>
</div>