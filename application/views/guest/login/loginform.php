<?php if (isset($err) && $err): ?>
<div class="error">Błąd: <?php echo $err; ?></div>
<?php endif; ?>
<form action="<?php echo URL::site('check_login'); ?>" method="POST">
    ID użytkownika: <input type="text" name="iduser"><br />
    Hasło: <input type="password" name="pass"><br />
    <input type="submit" value="Zaloguj">
</form>