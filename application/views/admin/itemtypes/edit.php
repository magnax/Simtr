Edytuj typ przedmiotu:
<?php echo Form::open(); ?>
<?php echo Form::hidden('id', $itemtype->id); ?>
<?php echo Form::hidden('redir', isset($redir) ? $redir : $request->referrer()); ?>
Nazwa: <?php echo Form::input('name', $itemtype->name); ?><br />
Typ projektu: <?php echo Form::select('projecttype_id', $projecctypes, $itemtype->projecttype_id); ?><br />
Siła ataku: <?php echo Form::input('attack', $itemtype->attack); ?><br/>
Siła obrony: <?php echo Form::input('shield', $itemtype->shield); ?><br/>
Waga: <?php echo Form::input('weight', $itemtype->weight); ?><br/>
Punkty (trwałość): <?php echo Form::input('points', $itemtype->points); ?><br/>
Widoczność: <?php echo Form::input('visible', $itemtype->visible); ?><br/>
Psucie/dzień: <?php echo Form::input('rot', $itemtype->rot); ?><br/>
Psucie używ./dzień: <?php echo Form::input('rot_use', $itemtype->rot_use); ?><br/>
Naprawa/godz.: <?php echo Form::input('repair', $itemtype->repair); ?><br/>
Rodzaj (M/K): <?php echo Form::select('kind', array('K' => 'K', 'M' => 'M'), $itemtype->kind); ?><br />
<?php echo Form::submit('add', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<a href="<?php echo isset($redir) ? $redir : $request->referrer(); ?>">Powrót</a>
