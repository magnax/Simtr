<div class="title_bar">Informacja o podawaniu</div>
Dźwigasz: <?php echo $character['eq_weight']; ?>:<br />
Maksymalna ilość <?php echo $res['name']; ?>, jaką możesz podać: <?php echo $res['amount']; ?><br />
<form action="<?php echo url::site('u/event/give'); ?>" method="POST">
    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">

    Ilość, jaką chcesz podać: <input name="amount">
    Komu: <?php echo form::select('character_id', $all_characters); ?>
    <input type="submit" value="Kontynuuj">
</form>