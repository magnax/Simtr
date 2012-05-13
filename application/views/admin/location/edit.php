Lokacja ID: <?php echo $location['id']; ?><br />
<?php echo View::factory('admin/location/_form', array('location'=>$location))->render(); ?>
<div>
    <h1>Surowce</h1>
    <?php if ($location['resources']): ?>
        <?php foreach ($location['resources'] as $res): ?>
            <div>
                <?php echo 'Nazwa: '.html::anchor('admin/resource/edit/'.$res['id'], $res['name']).' (ID: '.$res['id'].'), typ: '.$res['type'].
                    ', bazowy zbiór: '.$res['gather_base']; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        Brak surowców
    <?php endif; ?>
     <?php echo html::anchor('admin/resource/add/'.$location['id'], 'Dodaj surowiec'); ?>
</div>
<div>
    <h1>Drogi</h1>
    <?php if ($location['exits']): ?>
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
     <?php echo html::anchor('admin/road/add/'.$location['id'], 'Dodaj drogę'); ?>
</div>
