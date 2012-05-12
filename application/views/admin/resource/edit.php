Surowiec ID: <?php echo $resource['id']; ?>
<?php echo Form::open(); ?>
Nazwa: <?php echo Form::input('name', $resource['name']); ?><br />
Typ: <?php echo Form::input('type', $resource['type']); ?><br />
Podstawa: <?php echo Form::input('gather_base', $resource['gather_base']); ?><br />
<?php echo Form::submit('save', 'Zapisz'); ?>
<?php echo Form::close(); ?>
