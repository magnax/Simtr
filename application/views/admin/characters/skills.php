<h1>Umiejętności</h1>
<?php foreach ($skills as $skill): ?>
    <div>
        <?php echo $skill->skill->name; ?>: <?php echo $skill->level; ?>
    </div>
<?php endforeach; ?>

<a href="<?php echo URL::base(); ?>admin/characters/add_initial_skills/<?php echo $character->id; ?>">
    Add initial skills
</a>