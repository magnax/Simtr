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
     <?php echo Form::open(); ?>
        <?php echo Form::select('building_id', $orphan_buildings); ?>
        <?php echo Form::submit('append', 'Przypisz'); ?>
     <?php echo Form::close(); ?>
</div>