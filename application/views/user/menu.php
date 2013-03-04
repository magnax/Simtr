<?php echo HTML::anchor('user/menu', 'Strona gracza'); ?> |
<?php echo HTML::anchor('login/logout', 'Wyloguj się'); ?>
<? if ($is_admin): ?>
     | <?= HTML::anchor('admin', 'Administration'); ?>
     | <?= HTML::anchor('admin/system', 'System check'); ?>
<? endif; ?>

