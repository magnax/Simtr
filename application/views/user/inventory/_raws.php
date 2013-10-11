<div class="objects-menu">Surowce</div>
<div class="list">
    <?php foreach ($raws as $r): ?>
    <div>
        <a href="/events/put_raw/<?=$r['id']; ?>" title="Upuść surowiec">
            <i class="icon-signout icon-rotate-90 icon-large"></i> 
        </a>
        <a href="/events/give_raw/<?=$r['id']; ?>" title="Podaj surowiec">
            <i class="icon-signout icon-large"></i> 
        </a>
        <a href="/events/use_raw/<?=$r['id']; ?>" title="Użyj do projektu">
            <i class="icon-cogs icon-large"></i> 
        </a>
        <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?>
    </div>
    <?php endforeach; ?>
</div>
