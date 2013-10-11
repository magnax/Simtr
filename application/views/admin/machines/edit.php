edycja/dodawanie maszyny do lokacji
<?php echo Form::open(); ?>
    Typ: <?php echo Form::select('itemtype_id', $machine_types, $machine->itemtype_id); ?> <br />
    <?php echo Form::submit('submit', 'Dodaj'); ?> 
<?php echo Form::close(); ?>   