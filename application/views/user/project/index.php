<div class="title_bar">Projekty</div>
<?php foreach ($projects as $project): ?>
<div class="list <?php echo ($character['id']==$project['owner_id']) ? 'myproject' : 'project'; ?>">
    <div class="pull-left">
        <a href="<?php echo URL::base(); ?>/point/project/<?php echo $project['id']; ?>">
            <i class="icon-hand-right icon-large"></i>
        </a>
        <?php echo HTML::anchor('user/project/info/'.$project['id'], '<i class="icon-eye-open icon-large"></i>'); ?>
        <?php if ($character['project_id'] == $project['id']): ?>
            <?php echo HTML::anchor('user/project/leave/'.$project['id'], '<i class="icon-pause icon-large"></i>'); ?>
        <?php elseif (!$character['project_id']): ?>
            <?php if ($project['can_join']): ?>
                <?php echo HTML::anchor('user/project/join/'.$project['id'], '<i class="icon-wrench icon-large"></i>'); ?>
            <?php else: ?>
                <i class="icon-wrench icon-muted icon-large"></i>
            <?php endif; ?>
        <?php else: ?>
            <i class="icon-wrench icon-muted icon-large"></i>
        <?php endif; ?>

        <?php if ($project['can_delete']): ?>
            <?php echo HTML::anchor('user/project/destroy/'.$project['id'], '<i class="icon-remove icon-large"></i>'); ?>
        <?php else: ?>
            <i class="icon-remove icon-muted icon-large"></i>
        <?php endif; ?>
    </div>
    <div class="pull-left" style="margin-left: 10px;">
        <?php echo $project['name']; ?>
        <br />(<i class="<?php echo ($project['tools_required']) ? 'icon-exclamation-sign' : 'icon-check-sign'; ?>"></i>
        <span class="project_<?php echo $project['id']; ?>_percent"><?php echo $project['progress']; ?></span>, <?php echo Model_GameTime::formatDateTime($project['created_at'], "d-h:m"); ?>
        <?php echo Helper_View::CharacterName($project['owner_id'], $project['owner_name']); ?>)
        <?php if ($project['running']): ?>
            <i class="icon-group icon-large"></i> <?php echo $project['workers']; ?>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<div class="clear"></div>
