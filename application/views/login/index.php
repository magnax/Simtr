<div class="title_bar">
    Logowanie do gry
</div>

<div class="main_area">
<?php echo form::open(); ?>
    <ul>
        <li>
            <?php echo form::label('email', 'E-mail: '); ?>
            <?php echo form::input('email', null, array('placeholder'=>'Twój e-mail')); ?><br />
        </li>
        <li>
            <?php echo form::label('password', 'Hasło: '); ?>
            <?php echo form::password('password', null, array('placeholder'=>'Twoje hasło')); ?><br />
            <div class="errors"><?= Arr::path($errors, 'failed'); ?></div>
        </li>
        <li>
            <?= form::label('remember', 'Zapamiętaj mnie'); ?>
            <?= form::checkbox('remember'); ?>
        </li>
        <li>
            <?php echo form::submit('login', 'Zaloguj'); ?><br />
        </li>
    </ul>

<?php echo form::close(); ?>
<?php echo HTML::anchor('passremind', 'Przypomnij mi hasło'); ?>
    <br /><br /><p><b>WAŻNE!!</b> Jeśli coś nie działa jak powinno to proszę to zgłaszać najlepiej poprzez forum <a href="http://simtr-forum.magnax.pl" target="_new">http://simtr-forum.magnax.pl</a>. Zachęcam do rejestracji, czytania i pisania. Najbardziej aktualne informacje o postępach w pracach nad grą znajdują się w <a href="http://simtr-forum.magnax.pl/viewtopic.php?f=2&t=6&p=259#p259" target="_new">TYM WĄTKU</a>. Można też kontaktować się mailowo: magnax@gmail.com</p>
</div>