<?php if ($resource->id): ?>
    Edycja: <?php echo $resource->name; ?>(<?php echo $resource->id; ?>)
<?php else: ?>
    Dodawanie nowego surowca
<?php endif; ?>
<?php echo Form::open(); ?>
<?php echo Form::hidden('redir', isset($redir) ? $redir : $request->referrer()); ?>
<?php echo Form::hidden('id', $resource->id); ?>
Nazwa: <?php echo Form::input('name', $resource->name); ?><br />
Podstawa: <?php echo Form::input('gather_base', $resource->gather_base); ?><br />
Raw: <?php echo Form::checkbox('is_raw', 1, !!$resource->is_raw); ?><br />
Dopełniacz: <?php echo Form::input('d', $resource->d); ?><br />
<?php echo Form::submit('save', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<a href="<?php echo isset($redir) ? $redir : $request->referrer(); ?>">Powrót</a>
