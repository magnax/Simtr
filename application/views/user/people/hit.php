Uderz tę osobę
<?php echo Form::open(); ?>

    <?php echo Form::label('weapon', 'Wybierz broń:'); ?>
    <?php echo Form::select('weapon', $weapons); ?><br />

    <?php echo Form::label('strength', 'Z jaką siłą:'); ?>
    <?php echo Form::select('strength', $strengths); ?><br />

    <?php echo Form::submit('hit', 'Uderz'); ?>
    
<?php echo Form::close(); ?>