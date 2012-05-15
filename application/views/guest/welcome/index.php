<div id="translations">
    <?php include Kohana::find_file('views', 'common/lang_bar') ?>
</div>
<div id="title_bar">
    Main menu
</div>
<div id="description">
    Welcome to Simtr 2.
</div>
<div id="main_menu">
    <?php echo html::anchor('register', 'New user'); ?><br />
    <?php echo html::anchor('login', 'Login'); ?><br />
    <?php echo html::anchor('login/mailme', 'Send me test mail'); ?><br />
</div>
