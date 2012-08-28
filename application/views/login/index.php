<div class="title_bar">
    Login to Simtr
</div>

<div class="main_area">
<?php echo form::open(); ?>
    <ul>
        <li>
            <?php echo form::label('email', 'E-mail: '); ?>
            <?php echo form::input('email', null, array('placeholder'=>'Your e-mail')); ?><br />
        </li>
        <li>
            <?php echo form::label('password', 'Password: '); ?>
            <?php echo form::password('password', null, array('placeholder'=>'Your password')); ?><br />
            <div class="errors"><?= Arr::path($errors, 'failed'); ?></div>
        </li>
        <li>
            <?= form::label('remember', 'Remember Me'); ?>
            <?= form::checkbox('remember'); ?>
        </li>
        <li>
            <?php echo form::submit('login', 'Log in'); ?><br />
        </li>
    </ul>

<?php echo form::close(); ?>
<?php echo html::anchor('passremind', 'Przypomnij mi hasło'); ?>
</div>