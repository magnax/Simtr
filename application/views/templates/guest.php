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
        <div><?php echo html::anchor('/','Fabular (pre-alpha)'); ?></div>
        <?php echo $content; ?>
        </div>
        <?php include Kohana::find_file('views', 'common/footer') ?>
    </body>
</html>