Formularz rejestracji usera.
<form action="<?php echo URL::site('check_user'); ?>" method="POST">
    E-mail: <input type="text" name="email"><br />
    Password: <input type="text" name="pass"><br />
    <input type="submit" value="Rejestruj">
</form>
Na podany wyżej adres e-mail otrzymasz swój LoginID, którego będziesz używać do
logowania się do gry.<br /><br />