<?= Form::open(); ?>
<? if (Arr::get($town, 'id')): ?>
    <?= Form::hidden('id', $town['id']); ?>
<? endif; ?>
<ul>
    <li>
        <?php echo Form::label('name', 'Internal name'); ?>
        <?php echo Form::input('name', HTML::chars(Arr::get($location, 'name'))); ?>
        <div class="error"><?= Arr::get($errors, 'name'); ?></div>
    </li>
    <li>
        <?php echo Form::label('x', 'X'); ?>
        <?php echo Form::input('x', HTML::chars(Arr::get($town, 'x'))); ?>
        <div class="error"><?= Arr::get($errors, 'x'); ?></div>
    </li>
    <li>
        <?php echo Form::label('y', 'Y'); ?>
        <?php echo Form::input('y', HTML::chars(Arr::get($town, 'y'))); ?>
        <div class="error"><?= Arr::get($errors, 'y'); ?></div>
    </li>
    <li>
        <?php echo Form::label('slots', 'Number of slots'); ?>
        <?php echo Form::input('slots', HTML::chars(Arr::get($town, 'slots'))); ?>
        <div class="error"><?= Arr::get($errors, 'slots'); ?></div>
    </li>
    <li>
        <?= Form::submit('save', 'Save town'); ?>
    </li>
</ul>
<?= Form::close(); ?>
