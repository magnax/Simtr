Lokacja ID: <?php echo $location->id; ?><br />
<?php echo Form::open(); ?>
    Nazwa: <?php echo Form::input('name', $location->name); ?><br />
    Nadrzędna: <?php echo Form::select('parent_id', $possible_parent_locations, $location->parent); ?><br />
    Klasa: <?php echo Form::select('class_id', $location_classes, $location->class_id); ?><br />
    Typ: <?php echo Form::select('locationtype_id', $location_types, $location->locationtype_id); ?><br />
    <?php echo Form::submit('submit', 'Zapisz'); ?>  
<?php echo Form::close(); ?>
 
<?php echo View::factory('admin/location/forms/' . $location->locationtype->name)
        ->bind('location', $location_detail_object)
        ->bind('towns', $towns); 
?>
    
<?php if ($location->is_town()): ?>
    <h2>Surowce</h2>
    <?php if ($resources): ?>
        <?php foreach ($resources as $res): ?>
            <div>
                <?php echo HTML::anchor('admin/resource/edit/'.$res->id, $res->name); ?>
                (#<?php echo $res->id; ?>), bazowy zbiór: 
                <?php echo $res->gather_base; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Brak surowców
    <?php endif; ?>
     <?php echo HTML::anchor('admin/resource/add/'.$location->id, 'Dodaj surowiec'); ?>
<?php endif; ?>

<?php if ($location->is_workable()): ?>
    <h2>Maszyny</h2>
    <?php foreach ($machines as $machine): ?>
        <div><?php echo $machine->itemtype->name; ?></div>
    <?php endforeach; ?>
    <a href="<?php echo URL::base(); ?>admin/machines/add/<?php echo $location->id; ?>">Dodaj maszynę</a>
<?php endif; ?>