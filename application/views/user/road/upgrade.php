<div class="title_bar">
    Ulepszanie drogi
</div>

<div>
    Aby ulepszyć tę drogę do poziomu: <?php echo $roadtype->name; ?>, potrzebujesz:
</div>
<ul>
    <?php foreach ($raws as $raw): ?>
    <li><?php echo $raw->amount; ?> gram <?php echo $raw->resource->d; ?></li>
    <?php endforeach; ?>
</ul>
<div>
    Projekt będzie trwał: <?php echo Model_GameTime::formatDateTime($spec->time, 'd-h:m'); ?>.
</div>
<?php echo Form::open(); ?>
<button><a href="<?php echo URL::base(); ?>events">Cofnij</a></button> <?php echo Form::submit('submit', 'Ulepszaj!'); ?>
<?php echo Form::close(); ?>