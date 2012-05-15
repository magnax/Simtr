<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
    </head>
    <body>
        <div id="main">
        <?php if (isset($err) && $err): ?>
            <div class="error">Błąd: <?php echo $err; ?></div>
        <?php endif; ?>
        <?php if (isset($msg) && $msg): ?>
            <div class="message"><?php echo $msg; ?></div>
        <?php endif; ?>
        <div><?php echo html::anchor('/','Simtr 2'); ?></div>
        <div id="statistics">
            <?php include Kohana::find_file('views', 'common/stats') ?>
        </div>
        <?php echo $content; ?>
        </div>
    </body>
</html>