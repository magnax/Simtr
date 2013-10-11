<?php echo Form::open(); ?>
    x: <?php echo Form::input('x', $location->x); ?> <br />
    y: <?php echo Form::input('y', $location->y); ?><br />
    Liczba slotów: <?php echo Form::input('slots', $location->slots); ?><br />
    <?php echo Form::submit('submit', 'Zapisz dane osady'); ?> 
<?php echo Form::close(); ?>    