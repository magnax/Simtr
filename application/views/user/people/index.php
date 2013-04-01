<div class="title_bar">Ludzie</div>
<div id="people_list">
    <?php foreach ($characters as $ch): ?>
    <div>
        <a href="<?= URL::base(); ?>chname/<?=$ch->id; ?>" title="Info o postaci">
            <img src="/assets/images/info.png" height=32 width=32>
        </a>
        <a href="<?= URL::base(); ?>user/point/person/<?=$ch->id; ?>" title="Wskaż tę postać">
            <img src="/assets/images/point.png" height=32 width=32>
        </a>
        <a href="<?= URL::base(); ?>user/people/hit/<?=$ch->id; ?>" title="Atakuj tę postać">
            <img src="/assets/images/hit.png" height=32 width=32>
        </a>
        <?= HTML::image('assets/images/'.strtolower($ch->sex).'.png'); ?>
        <?= $ch->chname; ?>
    </div>
    <?php endforeach; ?>
</div>