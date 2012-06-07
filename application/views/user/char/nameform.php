Mów do tej postaci: <br />
<form action="<?php echo url::site('user/char/talkto'); ?>" method="POST">
    <input type="hidden" name="character_id" value="<?php echo $character_id; ?>">
    Co chcesz powiedzieć: <input type="text" name="text" value=""><br />
    <input type="submit" value="Mów">
</form>

Zmiana nazwy postaci<br />
Obecna nazwa: <?php echo $name; ?>
<form action="<?php echo url::site('user/char/namechange'); ?>" method="POST">
    <input type="hidden" name="character_id" value="<?php echo $character_id; ?>">
    Nowa nazwa: <input type="text" name="name" value="<?php echo $name; ?>"><br />
    <input type="submit" value="Zmień">
</form>
<p><?php echo html::anchor('user/people/hit/'.$character_id, '[Atakuj]'); ?></p>