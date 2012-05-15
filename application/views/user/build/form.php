<div class="title_bar">Budowanie</div>
Obiekt: <?php echo $production['object']['name']; ?><br />
Potrzebny czas: <?php echo $production['time']; ?><br />
Potrzebne surowce:
<?php foreach($production['raws'] as $raw): ?>
    <?php echo $raw['name']; ?> (<?php echo $raw['amount']; ?> gram)<br />
<?php endforeach; ?>
Przedmioty: <?php echo $production['items']; ?><br />
Potrzebne narzędzia: <?php echo $production['tools']; ?><br />
Potrzebne urządzenia: <?php echo $production['machines']; ?><br />
<form action="<?php echo url::site('u/build/start/'.$production['object']['item']); ?>" method="POST">
    <input type="submit" value="Kontynuuj">
</form>