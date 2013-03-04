<?php if ($character->life): ?>
Mów do tej postaci: <br />
<form action="<?php echo url::site('user/char/talkto'); ?>" method="POST">
    <input type="hidden" name="character_id" value="<?php echo $character_id; ?>">
    Co chcesz powiedzieć: <input type="text" name="text" value=""><br />
    <input type="submit" value="Mów">
</form>
<?php endif; ?>
Zmiana nazwy postaci<br />
Obecna nazwa: <?php echo $name; ?>
<?= form::open(); ?>
    <input type="hidden" name="character_id" value="<?php echo $character_id; ?>">
    Nowa nazwa: <input type="text" name="name" value="<?php echo $name; ?>"><br />
    <input type="submit" value="Zmień">
</form>
<?php if ($character->life): ?>
<p><?php echo HTML::anchor('user/people/hit/'.$character_id, '[Atakuj]'); ?></p>
<div id="char_stats">
    Obecny stan:<br />
    Zdrowie: <?php echo Utils::conditionBar($character->life); ?>
</div>
<?php endif; ?>