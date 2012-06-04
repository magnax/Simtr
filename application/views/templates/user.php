<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
    </head>
    <body>
        <div id="main">
            <div><?php echo html::anchor('/','Simtr 2'); ?></div>
            <div id="statistics">
                <?php include Kohana::find_file('views', 'common/stats') ?>
            </div>
            <div id="usermenu">
                <?php include Kohana::find_file('views', 'user/menu') ?>
            </div>

            <?php if (isset($character)): ?>
                <?php echo View::factory('user/charinfo', array('character'=>$character)); ?>
            <?php else: ?>
                <?php echo View::factory('user/userinfo', array('user'=>$user)); ?>
            <?php endif; ?>
            <?php echo $content; ?>
            <?php if (isset($character)): ?>
                <div id="buildmenu">
                    <?php include Kohana::find_file('views', 'user/buildmenu') ?>
                </div>
            <?php endif; ?>
        </div>
        <div id="kohana-profiler">
            <?php echo View::factory('profiler/stats') ?>
        </div>
    </body>
</html>