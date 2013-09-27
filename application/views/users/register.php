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

<?php if (count($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <? foreach (Arr::flatten($errors) as $error): ?>
                <li> <?php echo ucfirst($error); ?> </li>
            <? endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php echo Form::open(); ?>
<ul>
    <li>
        <?php echo Form::label('email', 'E-mail'); ?>
        <?php echo Form::input('email', HTML::chars(Arr::get($_POST, 'email'))); ?>
        <p>Dla celów testowych polecane jest skorzystanie z maila w serwisie <a href="http://mailinator.com">Mailinator.com</a> lub podobnego. </p>
    </li>
    <li>
        <?php echo Form::label('password', 'Hasło'); ?>
        <?php echo Form::password('password', HTML::chars(Arr::get($_POST, 'password'))); ?>
    </li>
    <li>
        <?php echo Form::label('password_confirm', 'Potwierdź hasło'); ?>
        <?php echo Form::password('password_confirm', HTML::chars(Arr::get($_POST, 'password_confirm'))); ?>
    </li>
    <li>
        <?php echo Form::label('rule_agreement', 'Potwierdzam przeczytanie zasad'); ?>
        <?php echo Form::checkbox('rule_agreement', 1, (bool) Arr::get($_POST, 'rule_agreement')); ?>
    </li>
    <li>
        <?php echo Form::submit('register', 'Zarejestruj'); ?>
    </li>
    
</ul>    
<?php echo Form::close(); ?>

Na podany wyżej adres e-mail otrzymasz wiadomość z informacjami potrzebnymi do aktywowania konta.<br /><br />
