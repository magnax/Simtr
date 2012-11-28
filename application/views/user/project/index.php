Projekty:<br />
<?php foreach ($projects as $project): ?>
<div class="<?php echo ($character['id']==$project['owner_id'])?'myproject':'project'; ?>">
        <?php echo html::anchor('user/project/info/'.$project['id'], '[Zobacz]'); ?>
        <?php if ($character['project_id'] == $project['id']): ?>
            <?php echo html::anchor('user/project/leave/'.$project['id'], '[Porzuć]'); ?>
        <?php elseif (!$character['project_id']): ?>
            <?php echo html::anchor('user/project/join/'.$project['id'], '[Dołącz]'); ?>
        <?php endif; ?>
        <?php echo $project['name']; ?>
        (<?php echo $project['progress']; ?>%, <?php echo Model_GameTime::formatDateTime($project['created_at'], "d-h:m"); ?>
        <?php echo $project['owner']; ?>)
        <?php if ($project['running']): ?>
            R (<?php echo $project['workers']; ?>)
        <?php endif; ?>
    </div>
<?php endforeach; ?>
