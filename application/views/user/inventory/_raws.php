<div class="list">
    <div class="objects-menu">Surowce</div>
    <?php foreach ($raws as $r): ?>
        <div>
        <a href="/events/put_raw/<?=$r['id']; ?>" title="Upuść surowiec">
            <img src="/assets/images/drop.png" height=32 width=32>
        </a>
        <a href="/events/give_raw/<?=$r['id']; ?>" title="Podaj surowiec">
            <img src="/assets/images/give.png" height=32 width=32>
        </a>
        <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
        </div>
    <?php endforeach; ?>
</div>
