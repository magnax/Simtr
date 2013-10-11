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
        <a href="/chname/<?php echo $worker['id']; ?>"><?php echo $worker['name']; ?></a><br />
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
    <?php if (!isset($project_specs) || !$project_specs): ?>
        Nie potrzeba żadnych do tego projektu.
    <?php else: ?>
        <?php foreach($project_specs as $spec): ?>
            <div>
                <?php echo $spec->resource->name; ?> 
                (Musisz jeszcze dodać <?php echo ($spec->needed - $spec->amount); ?> z potrzebnych <?php echo $spec->needed; ?> gram)
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
    <?php if (!count($mandatory_tools) && !count($optional_tools)): ?>
        Nie potrzeba żadnych do tego projektu.
    <?php else: ?>
        <?php foreach($mandatory_tools as $tool): ?>
            <div>
                <i class="<?php echo (in_array($tool->req_itemtype_id, $inventory_items_types)) ? 'icon-check' : 'icon-check-empty'; ?>"></i>
                <?php echo $tool->required_itemtype->name; ?>
            </div>
        <?php endforeach; ?>
        <?php foreach($optional_tools as $tool): ?>
            <div>
                <i class="<?php echo (in_array($tool->req_itemtype_id, $inventory_items_types)) ? 'icon-check' : 'icon-check-empty'; ?>"></i>
                <?php echo $tool->required_itemtype->name; ?> (opcja, zwiększa szybkość o <?php echo $tool->speed; ?>)
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
    <?php echo HTML::anchor('user/project/leave/'.$project['id'], '[Porzuć]'); ?>
<?php elseif (!$character['project_id']): ?>
    <?php if ($can_join): ?>
        <?php echo HTML::anchor('user/project/join/'.$project['id'], '[Dołącz]'); ?>
    <?php else: ?>
        <div class="error">
            Nie można rozpocząć projektu, póki nie są spełnione wszystkie wymagania.
        </div>
    <?php endif; ?>
<?php endif; ?>