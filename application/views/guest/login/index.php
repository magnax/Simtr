<div class="title_bar">
    Login to Simtr
</div>

<div class="main_area">
<?php echo form::open(); ?>
    <?php if ($errors): ?>
        <p class="message">Coś Ci się popierdoliło chyba, weź sprawdź te błędy i próbuj, kurwa, jeszcze raz!</p>
        <ul class="errors">
        <?php foreach ($errors as $message): ?>
            <li><?php echo $message ?></li>
        <?php endforeach ?>
    <?php endif ?>
    
    <?php echo form::label('email', 'E-mail: '); ?>
    <?php echo form::input('email', null, array('placeholder'=>'Your e-mail')); ?><br />
    
    <?php echo form::label('pass', 'Password: '); ?>
    <?php echo form::password('pass', null, array('placeholder'=>'Your password')); ?><br />
    
    <?php echo form::submit('login', 'Log in'); ?><br />
    
    <?php echo html::anchor('passremind', 'Przypomnij mi hasło'); ?>

<?php echo form::close(); ?>
</div>