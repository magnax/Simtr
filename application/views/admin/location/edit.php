Lokacja ID: <?php echo $location['id']; ?><br />
<?php echo View::factory('admin/location/_form', array('location'=>$location, 'types'=>$types, 'locations_classes'=>$locations_classes, 'level_types'=>$level_types))->render(); ?>
<div>
    <h1>Surowce</h1>
    <?php if ($location['resources']): ?>
        <?php foreach ($location['resources'] as $res): ?>
            <div>
                <?php echo 'Nazwa: '.HTML::anchor('admin/resource/edit/'.$res['id'], $res['name']).' (ID: '.$res['id'].'), typ: '.$res['type'].
                    ', bazowy zbiór: '.$res['gather_base']; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Brak surowców
    <?php endif; ?>
     <?php echo HTML::anchor('admin/resource/add/'.$location['id'], 'Dodaj surowiec'); ?>
</div>
<div>
    <h1>Drogi</h1>
    <?php if (isset($location['exits']) && $location['exits']): ?>
        <?php foreach ($location['exits'] as $exit): ?>
            <div>
                <?php echo 'Poziom: '.$exit['level'].' do: '.$exit['name'].
                    '('.$exit['lid'].') kierunek: '.$exit['direction'].
                    ' długość: '.$exit['distance']; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Brak dróg
    <?php endif; ?>
     <?php echo HTML::anchor('admin/road/add/'.$location['id'], 'Dodaj drogę'); ?>
</div>
<div>
     <h1>Budynki</h1>
    <?php if (isset($location['buildings']) && $location['buildings']): ?>
        <?php foreach ($location['buildings'] as $building): ?>
            <div>
                ID: <?php echo $building['id']; ?> <?php echo $building['name']; ?> 
                (<?php echo $building['type']; ?>) 
                <?php echo HTML::anchor('admin/location/removebuilding/'.$location['id'].'/'.$building['id'], '[Odłącz]'); ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Brak budynków
    <?php endif; ?>
     <?php echo HTML::anchor('admin/location/add', 'Dodaj budynek'); ?>
     <p>Dodaj nieprzypisany budynek do tej lokacji:
     <?php echo form::open(); ?>
        <?php echo form::select('building_id', $orphan_buildings); ?>
        <?php echo form::submit('append', 'Przypisz'); ?>
     <?php echo form::close(); ?>
</div>
