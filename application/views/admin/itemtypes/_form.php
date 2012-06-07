<?php echo form::open(); ?>
Nazwa: <?php echo form::input('name', $itemtype['name']); ?><br />
Siła ataku: <?php echo form::input('attack', $itemtype['attack']); ?><br/>
Siła obrony: <?php echo form::input('shield', $itemtype['shield']); ?><br/>
Waga: <?php echo form::input('weight', $itemtype['weight']); ?><br/>
Punkty (trwałość): <?php echo form::input('points', $itemtype['points']); ?><br/>
Widoczność: <?php echo form::input('visible', $itemtype['visible']); ?><br/>
Psucie/dzień: <?php echo form::input('rot', $itemtype['rot']); ?><br/>
Psucie używ./dzień: <?php echo form::input('rot_use', $itemtype['rot_use']); ?><br/>
Naprawa/godz.: <?php echo form::input('repair', $itemtype['repair']); ?><br/>
<?php echo form::submit('add', 'Save item type'); ?>
<?php echo form::close(); ?>
