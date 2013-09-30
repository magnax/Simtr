<div class="list">
    <div class="objects-menu">Surowce</div>
    <?php foreach ($raws as $r): ?>
        <div>
            <span style="font-size: 1.3em;">
                <a href="/events/put_raw/<?=$r['id']; ?>" title="Upuść surowiec">
                    <i class="icon-signout icon-rotate-90"></i> 
                </a>
                <a href="/events/give_raw/<?=$r['id']; ?>" title="Podaj surowiec">
                    <i class="icon-signout"></i> 
                </a>
                <a href="/events/use_raw/<?=$r['id']; ?>" title="Użyj do projektu">
                    <i class="icon-cogs "></i> 
                </a>
            </span>
            <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
        </div>
    <?php endforeach; ?>
</div>
