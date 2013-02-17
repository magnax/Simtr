<?php echo html::anchor('user/menu', 'Strona gracza'); ?> |
<?php echo html::anchor('login/logout', 'Wyloguj się'); ?>
<? if ($is_admin): ?>
     | <?= html::anchor('admin', 'Administration'); ?>
     | <?= html::anchor('admin/system', 'System check'); ?>
<? endif; ?>

