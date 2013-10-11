Przedmiot: <?php echo $itemtype->name; ?>
<?php echo Form::open(); ?>
<?php echo Form::hidden('id', $specs->id); ?>
<?php echo Form::hidden('itemtype_id', $itemtype->id); ?>
Menu: <?php echo Form::select('buildmenu_id', $menus, $specs->buildmenu_id); ?><br />
Czas: <?php echo Form::input('time', $specs->time); ?><br />
<?php echo Form::submit('add', 'Zapisz'); ?>
<?php echo Form::close(); ?>
<h2>Materiały:</h2>
<?php foreach ($raws as $raw): ?>
    <div>
        <?php echo $raw->resource->name; ?>: <?php echo $raw->amount; ?> gram 
        <a href="<?php echo URL::base(); ?>admin/specs/delete/<?php echo $raw->id; ?>">
            [X]
        </a>
    </div>
<?php endforeach; ?>
<?php echo HTML::anchor('admin/specs/add/'.$itemtype->id, 'Dodaj nowy materiał'); ?><br />

<h2>Narzędzia potrzebne do wykonywania projektu:</h2>
<h3>Niezbędne:</h3>
<?php if ($mandatory_tools): ?>
    <?php foreach ($mandatory_tools as $tool): ?>
        <div>
            <?php echo $tool->required_itemtype->name; ?>
            <a href="<?php echo URL::base(); ?>admin/tools/delete/<?php echo $tool->id; ?>">
                [X]
            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    Nie ma żadnych<br />
<?php endif; ?>
<h3>Opcjonalne (zwiększające szybkość pracy):</h3>
<?php if ($optional_tools): ?>
    <?php foreach ($optional_tools as $tool): ?>
        <div>
            <?php echo $tool->required_itemtype->name; ?>
            <a href="<?php echo URL::base(); ?>admin/tools/delete/<?php echo $tool->id; ?>">
                [X]
            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    Nie ma żadnych<br />
<?php endif; ?>
<?php echo HTML::anchor('admin/tools/add/'.$itemtype->id, 'Dodaj nowe'); ?><br />
<h2>Urządzenia:</h2>

<h2>Typy lokacji:</h2>

<h2>Obecność zasobu:</h2>

<a href="<?php echo $request->referrer(); ?>">Powrót</a>