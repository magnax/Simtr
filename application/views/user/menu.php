<?php echo html::anchor('user/menu', 'Strona gracza'); ?> |
<?php echo html::anchor('guest/login/logout', 'Wyloguj się'); ?>
<? if ($is_admin): ?>
     | <?= html::anchor('admin', 'Administration'); ?>
<? endif; ?>

