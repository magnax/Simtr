Lokacje (liczba wszystkich: <?php echo HTML::anchor('admin/location/all', $count); ?>)
<br />
<?php foreach ($locations as $location): ?>
    <?php echo HTML::anchor('admin/location/edit/'.$location['id'], $location['name'].' ('.$location['id'].')').'<br />'; ?>
<?php endforeach; ?>

<?php echo HTML::anchor('admin/location/add/', 'Dodaj lokację').'<br />'; ?>
