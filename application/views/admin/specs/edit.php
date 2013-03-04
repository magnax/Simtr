Edytuj wymaganie materiału:
<?php echo Form::open(); ?>
<?php echo Form::hidden('id', $spec->id); ?>
<?php echo Form::hidden('itemtype_id', $itemtype->id); ?>
<?php echo Form::hidden('redir', isset($redir) ? $redir : ''); ?>
Materiał: <?php echo Form::select('resource_id', $resources, $spec->resource_id); ?><br />
Ilość: <?php echo Form::input('amount', $spec->amount); ?><br />
<?php echo Form::submit('add', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<a href="<?php echo $request->referrer(); ?>">Powrót</a>