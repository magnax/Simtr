<?php echo form::open(); ?>
Imię: <?php echo form::input('name', $user['name']); ?><br />
Akt. życie: <?php echo form::input('life', isset($user['life']) ? $user['life'] : ''); ?><br />
Maks. życie: <?php echo form::input('vitality', isset($user['vitality']) ? $user['vitality'] : ''); ?><br />
<?php echo form::submit('save', 'Zapisz'); ?>
<?php echo form::close(); ?>

<p>Surowce:</p>
<?php if (count($raws)): ?>
    <?php foreach($raws as $raw): ?>
        <?php echo $raw['id']; ?>: <?php echo $raw['amount']; ?> g<br />
    <?php endforeach; ?>
<?php else: ?>
    Postać nie ma surowców
<?php endif; ?>
    <p>Dodaj surowiec:</p>
<?php echo form::open('/admin/characters/addraw/'.$user['id']); ?>
    Sur.: <?php echo form::input('id', ''); ?>, ilość: <?php echo form::input('amount', ''); ?>
    <?php echo form::submit('add', 'Dodaj sur.'); ?>
    <?php echo form::close(); ?>

<p>Przedmioty:</p>
<?php if (count($items)): ?>
    <?php foreach($items as $item): ?>
        <?php echo $item['id']; ?>: <?php echo $item['type']; ?> <?php echo $item['points']; ?><br />
    <?php endforeach; ?>
<?php else: ?>
    Postać nie ma przedmiotów
<?php endif; ?>
    <p>Dodaj przedmiot:</p>
<?php echo form::open('/admin/characters/additem/'.$user['id']); ?>
    Typ: <?php echo form::select('type', $itemtypes, ''); ?> Stan (punkty): <?php echo form::input('points', ''); ?>
    <?php echo form::submit('add_itemtype', 'Dodaj przedmiot.'); ?>
    <?php echo form::close(); ?>