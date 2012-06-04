<?php echo form::open(); ?>
<?php echo form::input('name', $user['name']); ?><br />
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
