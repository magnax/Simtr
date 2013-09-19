Wymagania:
<div>
    Surowce:
</div>
<div>
    <?php if (count($raws)): ?>
    <ul>
        <?php foreach ($raws as $raw): ?>
        <li>
            <?php echo $raw->resource->name; ?>: <?php echo $raw->amount; ?> gram
        </li>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
        Nie potrzeba żadnych
    <?php endif; ?>
</div>
Zajmie Ci to: <?php echo $spec->time; ?> sekund, sam sobie policz, ile to dni czy czego tam chcesz.<br />
<?php echo Form::open(); ?>
<?php echo Form::hidden('itemtype_id', $spec->itemtype_id); ?>
<?php if ($name_needed): ?>
    Nazwa budynku: <?php echo Form::input('building_name'); ?><br />
<?php endif; ?>
<?php echo Form::submit('submit', ' Na co czekasz, produkuj!! '); ?>
<?php echo Form::close(); ?>