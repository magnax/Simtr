<div class="title_bar">Budynki i pojazdy</div>
<?php foreach ($buildings as $building): ?>
    <?php echo html::anchor('user/location/enter/'.$building['id'], '[wejdź]'); ?> <?php echo $building['name']; ?><br />
<?php endforeach; ?>
