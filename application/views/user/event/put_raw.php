<div class="title_bar">Informacja o zostawianiu</div>
Dźwigasz: <?php echo $character['eq_weight']; ?>:<br />
Maksymalna ilość, jaką możesz upuścić: <?php echo $res['amount']; ?><br />
<form action="<?php echo url::site('u/event/put'); ?>" method="POST">
    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
    Ilość, jaką chcesz upuścić: <input name="amount"> <input type="submit" value="Kontynuuj">
</form>
