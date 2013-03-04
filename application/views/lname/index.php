Zmiana nazwy lokacji<br />
Obecna nazwa: <?php echo $name; ?>
<?= Form::open(); ?>
    <input type="hidden" name="location_id" value="<?php echo $location_id; ?>">
    Nowa nazwa: <input type="text" name="name" value="<?php echo $name; ?>"><br />
    <input type="submit" value="Zmień">
<?= Form::close(); ?>
