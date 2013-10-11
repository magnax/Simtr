Dodaj nowe narzędzie
<?php echo Form::open(); ?>
Projekt: <?php echo Form::select('itemtype_id', $itemtypes, $tool->itemtype_id); ?><br />
Narzędzie: <?php echo Form::select('req_itemtype_id', $itemtypes, $tool->req_itemtype_id); ?><br />
Opcjonalne: <?php echo Form::checkbox('optional', 1, $tool->optional); ?><br />
Wsp. prędkości: <?php echo Form::input('speed', $tool->speed); ?><br />
<?php echo Form::submit('submit', 'Dodaj'); ?>
<?php echo Form::close(); ?>