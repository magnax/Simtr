<?php echo Form::open(); ?>
    Max liczba osób: <?php echo Form::input('capacity_person', $location->capacity_person); ?> <br />
    Max pojemność (gram): <?php echo Form::input('max_weight', $location->max_weight); ?><br />
    <?php echo Form::submit('submit', 'Zapisz dane osady'); ?> 
<?php echo Form::close(); ?>   