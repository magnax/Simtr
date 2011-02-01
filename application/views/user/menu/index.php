<div class="title_bar">User info</div>
<?php include Kohana::find_file('views', 'user/userinfo') ?>
<div class="title_bar">Characters</div>
<?php if (isset($characters) && count($characters)): ?>
    <?php foreach ($characters as $character): ?>
        <?php echo html::anchor('char/'.$character['id'], $character['name']); ?>
        <?php echo $character['location']; ?> <?php echo $character['sex']; ?>
        <?php if(isset($character['project'])): ?>
            <?php echo $character['project']; ?>
        <?php endif; ?>
        <br />
    <?php endforeach; ?>
<?php else: ?>
    Nie masz aktualnie żadnych postaci w grze
<?php endif; ?>
<div>
    <?php echo html::anchor('u/char/newform', 'Twórz nową postać'); ?>
</div>
