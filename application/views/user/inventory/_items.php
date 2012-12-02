<div class="list">
    <div class="objects-menu">Przedmioty</div>   
    <?php foreach ($items as $item): ?>
    <div>
        <a href="/user/inventory/put/<?=$item['id']; ?>" title="Upuść przedmiot">
            <img src="/assets/images/drop.png" height=32 width=32>
        </a>
        <a href="/user/inventory/give/<?=$item['id']; ?>" title="Podaj przedmiot">
            <img src="/assets/images/give.png" height=32 width=32>
        </a>
        <?php echo $item['state']; ?> <?php echo $item['name']; ?><br />
    </div>
    <?php endforeach; ?>
</div>