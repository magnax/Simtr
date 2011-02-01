Zmiana nazwy miejsca<br />
Obecna nazwa: <?php echo $name; ?>
<form action="<?php echo url::site('u/location/change'); ?>" method="POST">
    <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
    Nowa nazwa: <input type="text" name="name" value="<?php echo $name; ?>"><br />
    <input type="submit" value="Zmień">
</form>