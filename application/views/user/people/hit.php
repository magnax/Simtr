Uderz tę osobę
<?php echo form::open(); ?>

    <?php echo form::label('weapon', 'Wybierz broń:'); ?>
    <?php echo form::select('weapon', $weapons); ?><br />

    <?php echo form::label('strength', 'Z jaką siłą:'); ?>
    <?php echo form::select('strength', $strengths); ?><br />

    <?php echo form::submit('hit', 'Uderz'); ?>
    
<?php echo form::close(); ?>