<div id="menu">
    <?php echo HTML::anchor('', 'Main page'); ?>
    <?php echo HTML::anchor('admin/users', 'Users'); ?> 
    <?php echo HTML::anchor('admin/location', 'Locations'); ?> 
    <?php echo HTML::anchor('admin/resource', 'Resources'); ?>
    <?php echo HTML::anchor('admin/fixtures/menu', 'Fixtures menu'); ?>
    <?php echo HTML::anchor('admin/characters', 'Characters menu'); ?> 
    <?php echo HTML::anchor('admin/itemtypes', 'Item types'); ?> 
    <?php echo HTML::anchor('admin/projecttypes', 'Project types'); ?> 
    <?php echo HTML::anchor('admin/keys', 'DB Keys'); ?> 
    Server: [<?php echo HTML::anchor($server_addr . ':9001', 'Supervisor', array('target' => '_new')); ?>]
</div>