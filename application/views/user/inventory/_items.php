<div class="objects-menu">Przedmioty</div>
<div class="list">
    <?php foreach ($items as $item): ?>
    <div>
        <a href="/user/inventory/put/<?=$item['id']; ?>" title="Upuść przedmiot">
            <i class="icon-signout icon-rotate-90 icon-large"></i>
        </a>
        <a href="/user/inventory/give/<?=$item['id']; ?>" title="Podaj przedmiot">
            <i class="icon-signout icon-large"></i>
        </a>
        <?php echo $item['state']; ?> <?php echo $item['name']; ?>
    </div>
    <?php endforeach; ?>
</div>