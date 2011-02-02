<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
    </head>
    <body>
        <div id="main">
<?php if (isset($err) && $err): ?>
    <div class="error">Błąd: <?php echo $err; ?></div>
<?php endif; ?>
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
        </div>
    </body>
</html>