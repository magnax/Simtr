<?php echo Form::open(); ?>
<?php echo Form::input('name', isset($tool['name']) ? $tool['name'] : ''); ?>
<?php echo Form::submit('submit', 'Dodaj'); ?>
<?php echo Form::close(); ?>