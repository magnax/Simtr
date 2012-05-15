Droga z: <?php echo $location->getID(); ?> (<?php echo $location->getX(); ?>, <?php echo $location->getY(); ?>)<br />
Do: 

<?php if (isset($destinations)): ?>
    <?php echo Form::open(); ?>
    <?php echo Form::select('destination_id', $destinations); ?>
    <?php echo Form::submit('destination', 'Wylicz drogę'); ?>
    <?php echo Form::close(); ?>
<?php endif; ?>

<?php if (isset($dest_location)): ?>
    <?php echo $dest_location->getID(); ?> (<?php echo $dest_location->getX(); ?>, <?php echo $dest_location->getY(); ?>)
    <br />
    <?php echo 'Długość: '.$distance; ?><br />
    <?php echo 'Kierunek: '.$direction.' ('.$direction_string.')'; ?><br />
    <?php echo 'Kierunek odwrotny: '.$rev_direction.' ('.$rev_direction_string.')'; ?><br />
    <?php echo Form::open(); ?>
    <?php echo Form::select('level', $levels); ?>
    <?php echo Form::hidden('destination_id', $dest_location->getID()); ?>
    <?php echo Form::submit('save', 'Zapisz drogę'); ?>
    <?php echo Form::close(); ?>
<?php endif; ?>
