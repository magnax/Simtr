<div class="title_bar">Informacja o podawaniu</div>
Dźwigasz: <?php echo $character['eq_weight']; ?>:<br />
Maksymalna ilość <?php echo $res['name']; ?>, jaką możesz podać: <?php echo $res['amount']; ?><br />
<?= Form::open(); ?>
    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">

    Ilość, jaką chcesz podać: <?php echo Form::input('amount', $max_amount); ?>
    Komu: <?php echo Form::select('character_id', $characters); ?>
    <input type="submit" value="Kontynuuj">
<?= Form::close(); ?>