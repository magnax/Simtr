Przedmiot: <?php echo $item->itemtype->name; ?><br />
<?php echo Form::open(); ?>
<?php echo Form::hidden('item_id', $item->id); ?>
Komu chcesz podać ten przedmiot: 
<?php echo Form::select('character_id', $characters); ?><br />
<?php echo Form::submit('submit', 'Podaj'); ?>
<?php echo Form::close(); ?>