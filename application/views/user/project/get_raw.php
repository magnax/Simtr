Zbieranie: <?= $resource['name']; ?><br />W ciągu jednego dnia jedna postać może zebrać maksymalnie <?php echo $resource['gather_base']; ?> gram.<br />
<?= Form::open(); ?>
    <input type="hidden" name="type_id" value="GetRaw">
    <input type="hidden" name="resource_id" value="<?php echo $resource['id']; ?>">
    Ilość do zebrania: <input type="text" name="amount"> <input type="submit" value="Utwórz">
<?= Form::close(); ?>
