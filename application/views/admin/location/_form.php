<?php echo Form::open(); ?>
x: <?php echo Form::input('x', isset($location['x'])?$location['x']:''); ?> 
y: <?php echo Form::input('y', isset($location['y'])?$location['y']:''); ?><br />
Nazwa (wewn.): <?php echo Form::input('name', isset($location['name']) ? $location['name'] : ''); ?><br />
Liczba slotów: <?php echo Form::input('res_slots', isset($location['res_slots']) ? $location['res_slots'] : ''); ?><br />

<?php echo Form::submit('submit', 'Zapisz'); ?>
<?php echo Form::close(); ?>