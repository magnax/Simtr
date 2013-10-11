<div class="title_bar">
    <?= $character['location']['str']; ?> - 
    [<?= $locationtype; ?>]: obiekty
</div>

<?php if ($lockable): ?>
    <div>Drzwi, poziom zamka: <? Helper_Lock::show_lock_level($lock)?>
        <? if ($lock->locktype->level): ?>
            <?= Helper_Lock::show_lock_status($lock)?>
            <?= Helper_Lock::show_lock_action($lock, $has_key)?>
        <? endif; ?>
        <? Helper_Lock::show_lock_upgrade($lock, $can_upgrade_lock) ?>
    </div>
<?php endif; ?>

<?php if (count($notes)): ?>
<div class="list">
    <div class="objects-menu">Notatki</div>
    <?php foreach ($notes as $note): ?>
    <div>
        <a href="/user/notes/get/<?=$note['id']; ?>" title="Podnieś notatkę">
            <img src="/assets/images/get.png" height=32 width=32>
        </a>
        <a href="/user/notes/copy/<?=$note['id']; ?>" title="Kopiuj notatkę">
            <img src="/assets/images/copy.png" height=32 width=32>
        </a>
        <a href="/user/notes/view/<?=$note['id']; ?>" title="Czytaj notatkę">
            <img src="/assets/images/view.png" height=32 width=32>
        </a>
        <?php echo $note['title']; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
        
<?php if (count($raws)): ?>
    <div class="objects-menu">Surowce</div>
    <div class="list">
        <?php foreach ($raws as $r): ?>
            <span style="font-size: 1.3em;">
                <?php echo HTML::anchor('events/get_raw/'.$r['id'], '<i class="icon-signin icon-rotate-270"></i>'); ?>
            </span>
            <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (count($machines)): ?>
    <div class="objects-menu">Urządzenia</div>
    <div class="list">
        <?php foreach ($machines as $item): ?>
            <?php echo HTML::anchor('user/location/use/'.$item->id, '<i class="icon-gear icon-large"></i>'); ?>
            <?php echo $item->itemtype->name; ?><br />
        <?php endforeach; ?>
    </div>
<?php endif; ?>
    
<?php if (count($items)): ?>
    <div class="objects-menu">Przedmioty</div>
    <div class="list">
        <?php foreach ($items as $item): ?>
            <span style="font-size: 1.3em;">
                <?php echo HTML::anchor('user/location/getitem/'.$item['id'], '<i class="icon-signin icon-rotate-270"></i>'); ?>
            </span>
            <?php echo $item['state']; ?> <?php echo $item['name']; ?><br />
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (count($corpses)): ?>
    <div class="objects-menu">Ciała</div>
    <?php foreach ($corpses as $corpse): ?>
        <?php echo HTML::anchor('user/location/bury/'.$corpse['id'], '[zakop]'); ?> Ciało <?php echo HTML::anchor('chname/'.$corpse['character_id'], $corpse['name']); ?><br />
    <?php endforeach; ?>
<?php endif; ?>