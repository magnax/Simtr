Zajebiście!! Nie potrzebujesz zupełnie nic, żeby wyprodukować ten przedmiot!<br />
Jesteś normalnie, kurwa, Bogiem<br />
Zajmie Ci to: <?php echo $spec->time; ?> sekund, sam sobie policz, ile to dni czy czego tam chcesz.<br />
<?php echo form::open(); ?>
<?php echo form::hidden('itemtype_id', $spec->itemtype_id); ?>
<?php echo form::submit('submit', ' Na co czekasz, produkuj!! '); ?>
<?php echo form::close(); ?>