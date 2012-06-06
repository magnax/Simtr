<div class="title_bar">Zdarzenia</div>
<div id="events">
    <?php echo form::open('user/event/talkall'); ?>
    <input id="event_input_small" type="text" name="text"> <input type="submit" value="Talk to all">
    <?php echo form::close(); ?>
<?php if (isset($events) && count($events)): ?>
    <?php foreach ($events as $event): ?>
        <?php if ($event['date'] == ''): ?>
            <?php if ($event['prev']): ?>
                <?php echo html::anchor('/user/event/index/'.$event['prev'], 'Poprzednia strona'); ?> &nbsp; &nbsp;
            <?php endif; ?>
            <?php if ($event['next']): ?>
                <?php echo html::anchor('/user/event/index/'.$event['next'], 'Następna strona'); ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo Model_GameTime::formatDateTime($event['date']); ?>: <?php echo $event['text']; ?><br/>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>