<div class="title_bar">Projekt: <?php echo $project['name']; ?></div>

<div class="table_left">
    Rozpoczynający:
</div>
<div class="table_right">
    <?php echo $project['owner']; ?> (<?php echo $project['date']; ?>)
</div>
<div class="clear"></div> 

<div class="table_left">
    Uczestnicy:
</div>
<div class="table_right">
    <?php foreach($project['workers'] as $worker): ?>
        <a href="/chname?id=<?php echo $worker['id']; ?>"><?php echo $worker['name']; ?></a><br />
    <?php endforeach; ?>
</div>
<div class="clear"></div> 

<div class="table_left">
    Postęp:
</div>
<div class="table_right">
    <?php echo $project['progress']; ?>
</div>
<div class="clear"></div> 

<div class="table_left">
    Potrzebny czas:
</div>
<div class="table_right">
    <?php echo $project['time']; ?>
</div>
<div class="clear"></div> 

<div class="table_left">
    Wykonywanie projektu:
</div>
<div class="table_right">
    Ręczne
</div>
<div class="clear"></div> 

<div class="table_left">
    Materiały:
</div>
<div class="table_right">
    <?php foreach($project['materials'] as $material): ?>
        <?php echo $material['name']; ?> (Musisz jeszcze dodać <?php echo $material['needed']; ?> z potrzebnych <?php echo $material['amount']; ?> gram)
    <?php endforeach; ?>
</div>
<div class="clear"></div> 

<div class="table_left">
    Przedmioty:
</div>
<div class="table_right">
    
</div>
<div class="clear"></div> 

<div class="table_left">
    Narzędzia:
</div>
<div class="table_right">
    
</div>
<div class="clear"></div> 

<?php if (isset($project['amount'])): ?>
    <div class="table_left">
        Ilość:
    </div>
    <div class="table_right">
        <?php echo $project['amount']; ?>
    </div>
    <div class="clear"></div>
<?php endif; ?>

<?php if ($character['project_id'] == $project['id']): ?>
    <?php echo html::anchor('user/project/leave/'.$project['id'], '[Porzuć]'); ?>
<?php elseif (!$character['project_id']): ?>
    <?php echo html::anchor('user/project/join/'.$project['id'], '[Dołącz]'); ?>
<?php endif; ?>