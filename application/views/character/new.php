<div class="title_bar">Utwórz nową postać</div>
<?= Form::open(); ?>
<ul>
    <li>
        <?php echo Form::label('name', 'Imię: '); ?>
        <?php echo Form::input('name', Arr::get($_POST, 'name')); ?><br />
        <div class="errors"><?= Arr::get($errors, 'name'); ?></div>
    </li>
    <li>
        <?php echo Form::label('sex', 'Płeć: '); ?>
        <?php echo Form::select('sex', array('0'=>'-- wybierz --', 'K' => 'Kobieta', 'M'=>'Mężczyzna'), Arr::get($_POST, 'sex')); ?><br />
        <div class="errors"><?= Arr::get($errors, 'sex'); ?></div>
    </li>
    <li>
        <?php echo Form::submit('submit', 'Create'); ?>
    </li>
</ul>
<?= Form::close(); ?>