<div class="title_bar">Location name - [location type]: obiekty</div>

<?php if (count($raws)): ?>
    <div class="objects-menu">Surowce</div>
    <?php foreach ($raws as $r): ?>
    <?php echo html::anchor('events/get_raw/'.$r['id'], '[podnieś]'); ?> <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($items)): ?>
    <div class="objects-menu">Przedmioty</div>
    <?php foreach ($items as $item): ?>
        <?php echo html::anchor('user/location/getitem/'.$item['id'], '[podnieś]'); ?>
        <?php echo $item['name']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($corpses)): ?>
    <div class="objects-menu">Ciała</div>
    <?php foreach ($corpses as $corpse): ?>
        Ciało <?php echo html::anchor('chname?id='.$corpse['character_id'], $corpse['name']); ?><br />
    <?php endforeach; ?>
<?php endif; ?>