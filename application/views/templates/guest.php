<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
    </head>
    <body>
        <div id="main">
        <?php if (isset($error) && $error): ?>
            <div class="error">Błąd: <?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($message) && $message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <div><?php echo HTML::anchor('/','Fabular (pre-alpha)'); ?></div>
        <?php echo $content; ?>
        </div>
        <?php include Kohana::find_file('views', 'common/footer') ?>
    </body>
</html>