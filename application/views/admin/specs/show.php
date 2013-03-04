Przedmiot: <?php echo $itemtype->name; ?>
<?php echo form::open(); ?>
<?php echo form::hidden('id', $specs->id); ?>
<?php echo form::hidden('itemtype_id', $itemtype->id); ?>
Menu: <?php echo form::select('buildmenu_id', $menus, $specs->buildmenu_id); ?><br />
Czas: <?php echo form::input('time', $specs->time); ?><br />
<?php echo form::submit('add', 'Zapisz'); ?>
<?php echo form::close(); ?>
<?php foreach ($raws as $raw): ?>
    <div>
        <?php echo $raw->resource->name; ?>: <?php echo $raw->amount; ?> gram 
        <a href="<?php echo URL::base(); ?>admin/specs/delete/<?php echo $raw->id; ?>">
            [X]
        </a>
    </div>
<?php endforeach; ?>
<?php echo HTML::anchor('admin/specs/add/'.$itemtype->id, 'Dodaj nowy materiał'); ?><br />
<a href="<?php echo $request->referrer(); ?>">Powrót</a>