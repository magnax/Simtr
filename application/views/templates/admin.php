<style>
    body {
        margin: 0px;
    }
    #menu {
        border: 1px dotted #3d77cb;
        margin-bottom: 15px;
        padding: 5px;
    }
</style>
<div id="menu">
    <?php echo html::anchor('admin/menu', 'Menu'); ?>
    <?php echo html::anchor('admin/location', 'Locations'); ?> 
    <?php echo html::anchor('admin/resource', 'Resources'); ?>
    Manufacturing [<?php echo html::anchor('admin/tools', 'Tools'); ?>]
    <?php echo html::anchor('admin/fixtures/menu', 'Fixtures menu'); ?>
    <?php echo html::anchor('admin/characters/menu', 'Characters menu'); ?> 
</div>
<?php echo $content; ?>
