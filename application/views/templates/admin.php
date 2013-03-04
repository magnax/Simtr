<style>
    body {
        margin: 0px;
    }
    #menu {
        border: 1px dotted #3d77cb;
        margin-bottom: 0px;
        padding: 0px;
        font-family: Verdana,sans-serif;
        font-size: 8pt;
        line-height: 21px;
    }
    #menu a {
        padding: 3px;
        text-decoration: none;
        background-color: #acd4f0;
        margin: 0px;
    }
</style>
<div id="menu">
    <?php echo HTML::anchor('', 'Main page'); ?>
    <?php echo HTML::anchor('admin/menu', 'Menu'); ?>
    <?php echo HTML::anchor('admin/users', 'Users'); ?> 
    <?php echo HTML::anchor('admin/location', 'Locations'); ?> 
    <?php echo HTML::anchor('admin/resource', 'Resources'); ?>
    <?php echo HTML::anchor('admin/fixtures/menu', 'Fixtures menu'); ?>
    <?php echo HTML::anchor('admin/characters/menu', 'Characters menu'); ?> 
    <?php echo HTML::anchor('admin/itemtypes', 'Item types'); ?> 
    <?php echo HTML::anchor('admin/projecttypes', 'Project types'); ?> 
    <?php echo HTML::anchor('admin/menu/keys', 'DB Keys'); ?> 
    Node server: [<?php echo HTML::anchor('admin/server/check', 'Check server'); ?> 
    <?php echo HTML::anchor('admin/server/on', 'Turn on server'); ?>]
    <?php echo HTML::anchor('admin/events/index', 'Events'); ?> 
    <?php echo HTML::anchor('admin/projects/index', 'Projects'); ?> 
    <?php echo HTML::anchor('admin/system', 'System'); ?> 
</div>
<?php echo $content; ?>
