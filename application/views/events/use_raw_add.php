Projekt wymaga <?php echo $needed_amount; ?> gram <?php echo $resource->name; ?>. Posiadasz: <?php echo $got_amount; ?> gram.
<?php echo Form::open(); ?>
<?php echo Form::hidden('project_id', $project->id); ?>
<?php echo Form::hidden('resource_id', $resource->id); ?>
<?php echo Form::input('amount', ($got_amount > $needed_amount) ? $needed_amount : $got_amount); ?>
<?php echo Form::submit('submit_raw', 'Dodaj'); ?>
<?php echo Form::close(); ?>
