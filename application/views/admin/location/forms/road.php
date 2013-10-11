<?php echo Form::open(); ?>
    1 lokacja: <?php echo Form::select('location_1_id', $towns, $location->location_1_id); ?> <br />
    2 lokacja: <?php echo Form::select('location_2_id', $towns, $location->location_2_id); ?> <br />
    <?php echo Form::submit('submit', 'Zapisz dane'); ?> 
<?php echo Form::close(); ?>
