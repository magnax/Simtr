<div>
    <?= HTML::anchor('/','Fabular (pre-alpha)'); ?> 
    (U:<span id="count_active_users">0</span>, C:<span id="count_active_chars">0</span>)
</div>
<?php if (isset($error) && $error): ?>
    <div class="error">Błąd: <?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($message) && $message): ?>
    <div class="message"><?php echo $message; ?></div>
<?php endif; ?>
<div id="statistics">
    <?= $stats; ?>
</div>
<div id="usermenu">
    <?= $user_menu; ?>
</div>