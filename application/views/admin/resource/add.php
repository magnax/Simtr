Dodaj surowiec do lokacji
<?php echo Form::open(); ?>
<?php echo Form::select('resource_id', $resources); ?>
<?php echo Form::submit('submit', 'Dodaj'); ?>
<?php echo Form::close(); ?>
