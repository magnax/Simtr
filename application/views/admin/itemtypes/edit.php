Edytuj typ przedmiotu:
<?php echo Form::open(); ?>
<?php echo Form::hidden('id', $itemtype->id); ?>
Nazwa: <?php echo Form::input('name', $itemtype->name); ?><br />
Siła ataku: <?php echo Form::input('attack', $itemtype->attack); ?><br/>
Siła obrony: <?php echo Form::input('shield', $itemtype->shield); ?><br/>
Waga: <?php echo Form::input('weight', $itemtype->weight); ?><br/>
Punkty (trwałość): <?php echo Form::input('points', $itemtype->points); ?><br/>
Widoczność: <?php echo Form::input('visible', $itemtype->visible); ?><br/>
Psucie/dzień: <?php echo Form::input('rot', $itemtype->rot); ?><br/>
Psucie używ./dzień: <?php echo Form::input('rot_use', $itemtype->rot_use); ?><br/>
Naprawa/godz.: <?php echo Form::input('repair', $itemtype->repair); ?><br/>
<?php echo Form::submit('add', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<a href="<?php echo $request->referrer(); ?>">Powrót</a>
