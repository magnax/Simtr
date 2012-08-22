<?php echo Form::open(); ?>
<?php echo form::hidden('parent', isset($location['parent']) ? $location['parent'] : ''); ?>
    Klasa: <?php echo Form::select('class', $locations_classes, isset($location['class']) ? $location['class'] : '--'); ?><br />
    Typ: <?php echo Form::select('type', $types, isset($location['type']) ? $location['type'] : '--'); ?><br />
    Nazwa (wewn.): <?php echo Form::input('name', isset($location['name']) ? $location['name'] : ''); ?><br />
    
        x: <?php echo Form::input('x', isset($location['x']) ? $location['x'] : ''); ?> 
        y: <?php echo Form::input('y', isset($location['y']) ? $location['y'] : ''); ?><br />
    Liczba slotów: <?php echo Form::input('slots', isset($location['slots']) ? $location['slots'] : ''); ?><br />
    Maks. ciężar (g): <?php echo Form::input('capacity', isset($location['capacity']) ? $location['capacity'] : ''); ?><br />
    Poziom drogi: <?php echo Form::select('level', $level_types, isset($location['level']) ? $location['level'] : '--'); ?><br />
    
    
    
    
    
    <?php echo Form::submit('submit', 'Zapisz'); ?>
    
<?php echo Form::close(); ?>