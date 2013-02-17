Projekt, do którego chcesz użyć <?php echo $resource->name; ?>
<?php echo Form::open(); ?>
<?php echo Form::select('project_id', $projects); ?>
<?php echo Form::submit('submit_project', 'Dalej'); ?>
<?php echo Form::close(); ?>