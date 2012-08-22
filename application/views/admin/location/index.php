Lokacje (liczba wszystkich: <?php echo html::anchor('admin/location/all', $count); ?>)
<br />
<h2>Miasta:</h2>
<?php foreach ($towns as $location): ?>
    <?php echo html::anchor('admin/location/edit/'.$location['id'], $location['name'].' ('.$location['id'].')').'<br />'; ?>
<?php endforeach; ?>

<h2>Budynki:</h2>
<?php foreach ($buildings as $location): ?>
    <?php echo html::anchor('admin/location/edit/'.$location['id'], $location['name'].' ('.$location['id'].')').' ==> '.$location['parent'].'<br />'; ?>
<?php endforeach; ?>

<p><?php echo html::anchor('admin/location/add/', 'Dodaj lokację').'<br />'; ?>
