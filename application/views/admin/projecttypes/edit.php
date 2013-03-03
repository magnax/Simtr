Edytuj typ projektu:
<?php echo form::open(); ?>
<?php echo form::hidden('id', $projecttype->id); ?>
<?php echo form::hidden('redir', isset($redir) ? $redir : ''); ?>
Nazwa: <?php echo form::input('name', $projecttype->name); ?><br />
Klucz: <?php echo form::input('key', $projecttype->key); ?><br />
Opis: <?php echo form::input('description', $projecttype->description); ?><br />
<?php echo form::submit('add', 'Zapisz'); ?>
<?php echo form::close(); ?>
<a href="<?php echo $request->referrer(); ?>">Powrót</a>