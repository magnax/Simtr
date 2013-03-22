<div class="title_bar">Informacja o zabieraniu</div>
Dźwigasz <?php echo $character['eq_weight']; ?> gram.<br />
Na ziemi znajduje się <?php echo $res['amount']; ?> gram <?php echo $res['name']; ?>.<br />
Maksymalna ilość <?php echo $res['name']; ?>, jaką możesz podnieść: <?php echo $max_amount; ?> gram.<br />
<?= Form::open(); ?>
    <input type="hidden" name="res_id" value="<?php echo $res['id']; ?>">
    Ilość, jaką chcesz podnieść: 
    <?php echo Form::input('amount', $max_amount); ?>
    <input type="submit" value="Kontynuuj">
<?= Form::close(); ?>
