<div class="title_bar">
    Wskazówki dla nowych graczy
</div>
<div id="description">
    <p>Witamy w Fabular. Przeczytaj uważnie poniższe wskazówki.</p>
    <p>Fabular jest w bardzo wczesnej wersji - nie jest to nawet beta, raczej
    wstępne demo z kilkunastoma podstawowymi funkcjonalnościami. Ale sukcesywnie, 
    choć powoli przekształca się to w prawdziwy symulator
    społeczności, dokładane są nowe funkcjonalności, poprawiane istniejące.</p>
    
</div>

<div class="title_bar">Zarejestruj się</div>
<?php echo form::open(); ?>
<ul>
    <li>
        <?php echo form::label('email', 'E-mail'); ?>
        <?php echo form::input('email', HTML::chars(Arr::get($_POST, 'email'))); ?>
        <div class="error"><?= Arr::get($errors, 'email'); ?></div>
        <p>Dla celów testowych polecane jest skorzystanie z maila w serwisie <a href="http://mailinator.com">Mailinator.com</a> lub podobnego. </p>
    </li>
    <li>
        <?php echo form::label('password', 'Hasło'); ?>
        <?php echo form::password('password', HTML::chars(Arr::get($_POST, 'password'))); ?>
        <div class="error"><?= Arr::path($errors, '_external.password'); ?></div>
    </li>
    <li>
        <?php echo form::label('password_confirm', 'Potwierdź hasło'); ?>
        <?php echo form::password('password_confirm', HTML::chars(Arr::get($_POST, 'password_confirm'))); ?>
        <div class="error">
            <?= Arr::path($errors, '_external.password_confirm'); ?>
        </div>
    </li>
    <li>
        <?php echo form::label('rule_agreement', 'Potwierdzam przeczytanie zasad'); ?>
        <?php echo form::checkbox('rule_agreement', 1, (bool) Arr::get($_POST, 'rule_agreement')); ?>
        <?php if (isset($errors['rule_agreement'])): ?>
            <div class="error"><?php echo $errors['rule_agreement']; ?></div>
        <?php endif; ?>
    </li>
    <li>
        <?php echo form::submit('register', 'Zarejestruj'); ?>
    </li>
    
</ul>    
<?php echo form::close(); ?>

Na podany wyżej adres e-mail otrzymasz wiadomość z informacjami potrzebnymi do aktywowania konta.<br /><br />
