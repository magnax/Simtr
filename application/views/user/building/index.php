<div class="title_bar">Budynki i pojazdy</div>
<?php foreach ($buildings as $building): ?>
    <?php echo HTML::anchor('user/location/enter/'.$building->location->id, '[wejdź]'); ?> 
    <?php echo $building->location->name; ?>
    <?php if ($building->location->lock->locked): ?>
        (<strong>L</strong>)
    <?php endif; ?>
    <br />
<?php endforeach; ?>
