<style>
    body {
        margin: 0px;
    }
    #menu {
        border: 1px dotted #3d77cb;
        margin-bottom: 15px;
        padding: 5px;
        font-family: Verdana,sans-serif;
        font-size: 8pt;
    }
    #menu a {
        padding: 3px;
        text-decoration: none;
        background-color: #acd4f0;
        margin: 2px;
    }
</style>
<div id="menu">
    <?php echo html::anchor('', 'Main page'); ?>
    <?php echo html::anchor('admin/menu', 'Menu'); ?>
    <?php echo html::anchor('admin/users', 'Users'); ?> 
    <?php echo html::anchor('admin/location', 'Locations'); ?> 
    <?php echo html::anchor('admin/resource', 'Resources'); ?>
    Manufacturing [<?php echo html::anchor('admin/tools', 'Tools'); ?>]
    <?php echo html::anchor('admin/fixtures/menu', 'Fixtures menu'); ?>
    <?php echo html::anchor('admin/characters/menu', 'Characters menu'); ?> 
    <?php echo html::anchor('admin/itemtypes/menu', 'Item types'); ?> 
    <?php echo html::anchor('admin/menu/keys', 'DB Keys'); ?> 
    Node server: [<?php echo html::anchor('admin/server/check', 'Check server'); ?> 
    <?php echo html::anchor('admin/server/on', 'Turn on server'); ?>]
</div>
<?php echo $content; ?>
