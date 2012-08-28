<div class="title_bar">Utwórz nową postać</div>
<?= form::open(); ?>
<ul>
    <li>
        <?php echo form::label('name', 'Imię: '); ?>
        <?php echo form::input('name', Arr::get($_POST, 'name')); ?><br />
        <div class="errors"><?= Arr::get($errors, 'name'); ?></div>
    </li>
    <li>
        <?php echo form::label('sex', 'Płeć: '); ?>
        <?php echo form::select('sex', array('0'=>'-- wybierz --', 'K' => 'Kobieta', 'M'=>'Mężczyzna'), Arr::get($_POST, 'sex')); ?><br />
        <div class="errors"><?= Arr::get($errors, 'sex'); ?></div>
    </li>
    <li>
        <?php echo form::submit('submit', 'Create'); ?>
    </li>
</ul>
<?= form::close(); ?>