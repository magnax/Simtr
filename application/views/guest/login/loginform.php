<form action="<?php echo URL::site('guest/login/checklogin'); ?>" method="POST">
    ID użytkownika: <input type="text" name="iduser"><br />
    Hasło: <input type="password" name="pass"><br />
    <input type="submit" value="Zaloguj">
    <?php echo html::anchor('/passremind', 'Przypomnij mi hasło'); ?>
</form>