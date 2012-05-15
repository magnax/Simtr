<?php echo Form::open(); ?>
<?php echo Form::hidden('redir', isset($redir)?$redir:$_SERVER['HTTP_REFERER']); ?>
<?php echo Form::hidden('id', isset($resource['id']) ? $resource['id'] : ''); ?>
Nazwa: <?php echo Form::input('name', isset($resource['name']) ? $resource['name'] : ''); ?><br />
Typ: <?php echo Form::input('type', isset($resource['type']) ? $resource['type'] : ''); ?><br />
Podstawa: <?php echo Form::input('gather_base', isset($resource['gather_base']) ? $resource['gather_base'] : ''); ?><br />
Raw: <?php echo Form::checkbox('is_raw', 1, isset($resource['is_raw']) && $resource['is_raw']); ?><br />
<?php echo Form::submit('save', 'Zapisz'); ?>
<?php echo Form::close(); ?>
