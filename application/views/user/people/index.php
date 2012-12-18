<div class="title_bar">Ludzie</div>
<div id="people_list">
    <?php foreach ($characters as $ch): ?>
    <div>
        <a href="<?= URL::base(); ?>chname?id=<?=$ch['id']; ?>" title="Info o postaci">
            <img src="/assets/images/info.png" height=32 width=32>
        </a>
        <a href="<?= URL::base(); ?>user/point/person/<?=$ch['id']; ?>" title="Wskaż tę postać">
            <img src="/assets/images/point.png" height=32 width=32>
        </a>
        <a href="<?= URL::base(); ?>user/people/hit/<?=$ch['id']; ?>" title="Atakuj tę postać">
            <img src="/assets/images/hit.png" height=32 width=32>
        </a>
        <?= html::image('assets/images/'.strtolower($ch['gender']).'.png'); ?>
        <?= $ch['name']; ?>
    </div>
    <?php endforeach; ?>
</div>