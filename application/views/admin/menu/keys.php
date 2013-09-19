<?php echo Form::open(); ?>
<?php echo Form::input('pattern', $pattern); ?>
<?php echo Form::submit('submit', 'pokaż'); ?>
<?php echo Form::close(); ?>
<h2><?php echo count($keys); ?></h2>
<?php foreach ($keys as $key): ?>
    &nbsp;&nbsp;<?php echo $key; ?><a href="<?php echo URL::base(); ?>admin/keys/delete/<?php echo $key; ?>">usuń</a><br/>
<?php endforeach; ?>