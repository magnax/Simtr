Rejestracja nowego usera
<div class="title_bar">
    Guide for new players
</div>
<div id="description">
    Witamy w grze Simtr 2. Przeczytaj uważnie poniższe wskazówki.
</div>

<div class="title_bar">Zarejestruj się</div>
<?php echo form::open(); ?>
<ul>
    <li>
        <?php echo form::label('email', 'E-mail'); ?>
        <?php echo form::input('email', HTML::chars(Arr::get($_POST, 'email'))); ?>
        <div class="error"><?= Arr::get($errors, 'email'); ?></div>
    </li>
    <li>
        <?php echo form::label('password', 'Password'); ?>
        <?php echo form::password('password', HTML::chars(Arr::get($_POST, 'password'))); ?>
        <div class="error"><?= Arr::path($errors, '_external.password'); ?></div>
    </li>
    <li>
        <?php echo form::label('password_confirm', 'Confirm password'); ?>
        <?php echo form::password('password_confirm', HTML::chars(Arr::get($_POST, 'password_confirm'))); ?>
        <div class="error">
            <?= Arr::path($errors, '_external.password_confirm'); ?>
        </div>
    </li>
    <li>
        <?php echo form::label('rule_agreement', 'Yes, I have read
    and accept the terms'); ?>
        <?php echo form::checkbox('rule_agreement', 1, (bool) Arr::get($_POST, 'rule_agreement')); ?>
        <?php if (isset($errors['rule_agreement'])): ?>
            <div class="error"><?php echo $errors['rule_agreement']; ?></div>
        <?php endif; ?>
    </li>
    <li>
        <?php echo form::submit('register', 'Register'); ?>
    </li>
    
</ul>    
<?php echo form::close(); ?>

Na podany wyżej adres e-mail otrzymasz wiadomość z informacjami potrzebnymi do aktywowania konta.<br /><br />
