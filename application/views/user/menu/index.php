<div class="title_bar">Postacie</div>
<?php if (isset($characters) && count($characters)): ?>
    <?php foreach ($characters as $character): ?>
    <div class="character" id="character-<?=$character['id']?>">
        <div class="character_sex">
            <?php echo $character['sex']; ?>
        </div>
        <div class="character_name">
            <?php if ($character['rip']): ?>
                R.I.P. 
            <?php endif; ?>
            <?php echo HTML::anchor('character?id='.$character['id'], $character['name']); ?>
        </div>
        <div class="character_location">
            <?php echo $character['location']; ?>
        </div>
        <div class="character_project">
            <?php if(isset($character['project'])): ?>
                <span id="project-<?= $character['id']; ?>"><?php echo $character['project']; ?></span>
            <?php endif; ?>
        </div>
        <div class="character_events">
            <? if ($character['new_events']): ?>
                <?= $character['new_events']; ?> new
            <? endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    Nie masz aktualnie żadnych postaci w grze
<?php endif; ?>
<div>
    <?php echo HTML::anchor('character/new', 'Twórz nową postać'); ?>
</div>
