<?php if (isset($err) && $err): ?>
<div class="error">Błąd: <?php echo $err; ?></div>
<?php endif; ?>
<form action="<?php echo URL::site('passremind/send'); ?>" method="POST">
    ID użytkownika lub email: <input type="text" name="id_email"><br />
    <input type="submit" value="Wyślij">
</form>
