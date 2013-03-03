Edytuj wymaganie materiału:
<?php echo form::open(); ?>
<?php echo form::hidden('id', $spec->id); ?>
<?php echo form::hidden('itemtype_id', $itemtype->id); ?>
<?php echo form::hidden('redir', isset($redir) ? $redir : ''); ?>
Materiał: <?php echo form::select('resource_id', $resources, $spec->resource_id); ?><br />
Ilość: <?php echo form::input('amount', $spec->amount); ?><br />
<?php echo form::submit('add', 'Zapisz'); ?>
<?php echo form::close(); ?>
<a href="<?php echo $request->referrer(); ?>">Powrót</a>