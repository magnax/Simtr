<?php echo Form::open(); ?>
Imię: <?php echo Form::input('name', $user['name']); ?><br />
Akt. życie: <?php echo Form::input('life', isset($user['life']) ? $user['life'] : ''); ?><br />
Maks. życie: <?php echo Form::input('vitality', isset($user['vitality']) ? $user['vitality'] : ''); ?><br />
<?php echo Form::submit('save', 'Zapisz'); ?>
<?php echo Form::close(); ?>

<p>Surowce:</p>
<?php if (count($raws)): ?>
    <?php foreach($raws as $raw): ?>
        <?php echo $raw['id']; ?>: <?php echo $raw['amount']; ?> g<br />
    <?php endforeach; ?>
<?php else: ?>
    Postać nie ma surowców
<?php endif; ?>
    <p>Dodaj surowiec:</p>
<?php echo Form::open('/admin/characters/addraw/'.$user['id']); ?>
    Sur.: <?php echo Form::input('id', ''); ?>, ilość: <?php echo Form::input('amount', ''); ?>
    <?php echo Form::submit('add', 'Dodaj sur.'); ?>
    <?php echo Form::close(); ?>

<p>Przedmioty:</p>
<?php if (count($items)): ?>
    <?php foreach($items as $item): ?>
        <?php echo $item['id']; ?>: <?php echo $item['type']; ?> <?php echo $item['points']; ?><br />
    <?php endforeach; ?>
<?php else: ?>
    Postać nie ma przedmiotów
<?php endif; ?>
    <p>Dodaj przedmiot:</p>
<?php echo Form::open('/admin/characters/additem/'.$user['id']); ?>
    Typ: <?php echo Form::select('type', $itemtypes, ''); ?> Stan (punkty): <?php echo Form::input('points', ''); ?>
    <?php echo Form::submit('add_itemtype', 'Dodaj przedmiot.'); ?>
    <?php echo Form::close(); ?>