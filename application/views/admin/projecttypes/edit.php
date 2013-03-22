Edytuj typ projektu:
<?php echo Form::open(); ?>
<?php echo Form::hidden('id', $projecttype->id); ?>
<?php echo Form::hidden('redir', isset($redir) ? $redir : $request->referrer()); ?>
Nazwa: <?php echo Form::input('name', $projecttype->name); ?><br />
Klucz: <?php echo Form::input('key', $projecttype->key); ?><br />
Opis: <?php echo Form::input('description', $projecttype->description); ?><br />
<?php echo Form::submit('add', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<a href="<?php echo isset($redir) ? $redir : $request->referrer(); ?>">Powrót</a>