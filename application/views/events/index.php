<div class="title_bar">Zdarzenia</div>
<div id="talk_form">
    <?php echo form::open('/events/talkall', array('id'=>'talk_all')); ?>
        <input id="event_input_small" type="text" name="text"> <input id="talk" type="submit" value="Mów do wszystkich">
    <?php echo form::close(); ?>
    <div id="talk_error" class="error"></div>
</div>
<div class="clear"></div>
<div id="events">
<ul>    
<?php if (isset($events) && count($events)): ?>
    
    <?php foreach ($events as $event): ?>
        <li<?= ($first_new_event && ($event['id'] >= $first_new_event))? ' class="new_event"':'' ?>>
        <?php if ($event['date'] == ''): ?>
            <?php if ($event['prev']): ?>
                <?php echo html::anchor('/events/p/'.$event['prev'], 'Poprzednia strona'); ?> &nbsp; &nbsp;
            <?php endif; ?>
            <?php if ($event['next']): ?>
                <?php echo html::anchor('/events/p/'.$event['next'], 'Następna strona'); ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo $event['date']; ?>: <?php echo $event['text']; ?><br/>
        <?php endif; ?>
        </li>
    <?php endforeach; ?>
    
<?php endif; ?>
</ul>
</div>