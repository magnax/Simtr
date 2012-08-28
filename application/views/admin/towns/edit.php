<?= form::open(); ?>
<? if (Arr::get($town, 'id')): ?>
    <?= form::hidden('id', $town['id']); ?>
<? endif; ?>
<ul>
    <li>
        <?php echo form::label('name', 'Internal name'); ?>
        <?php echo form::input('name', HTML::chars(Arr::get($location, 'name'))); ?>
        <div class="error"><?= Arr::get($errors, 'name'); ?></div>
    </li>
    <li>
        <?php echo form::label('x', 'X'); ?>
        <?php echo form::input('x', HTML::chars(Arr::get($town, 'x'))); ?>
        <div class="error"><?= Arr::get($errors, 'x'); ?></div>
    </li>
    <li>
        <?php echo form::label('y', 'Y'); ?>
        <?php echo form::input('y', HTML::chars(Arr::get($town, 'y'))); ?>
        <div class="error"><?= Arr::get($errors, 'y'); ?></div>
    </li>
    <li>
        <?php echo form::label('slots', 'Number of slots'); ?>
        <?php echo form::input('slots', HTML::chars(Arr::get($town, 'slots'))); ?>
        <div class="error"><?= Arr::get($errors, 'slots'); ?></div>
    </li>
    <li>
        <?= form::submit('save', 'Save town'); ?>
    </li>
</ul>
<?= form::close(); ?>
