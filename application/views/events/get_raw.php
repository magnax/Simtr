<div class="title_bar">Informacja o zabieraniu</div>
Dźwigasz: <?php echo $character['eq_weight']; ?> g<br />
Maksymalna ilość <?php echo $res['name']; ?>, jaką możesz podnieść: <?php echo $res['amount']; ?> g<br />
<form action="<?php echo url::site('user/event/get'); ?>" method="POST">
    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
    Ilość, jaką chcesz podnieść: <input name="amount"> <input type="submit" value="Kontynuuj">
</form>
