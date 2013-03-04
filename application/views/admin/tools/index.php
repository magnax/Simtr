Narzędzia:<br />
<?php if (isset($tools)): ?>

<?php else: ?>
Nie ma żadnych narzędzi jeszcze
<?php endif; ?>
<br />
<?php echo HTML::anchor('admin/tools/add', 'Dodaj nowe narzędzie'); ?>
