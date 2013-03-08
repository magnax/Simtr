<div class="title_bar">Zdarzenia</div>
<div id="talk_form">
    <?php echo Form::open('/events/talkall', array('id'=>'talk_all')); ?>
        <input id="event_input_small" type="text" name="text"> <input id="talk" type="submit" value="Mów do wszystkich">
    <?php echo Form::close(); ?>
    <div id="talk_error" class="error"></div>
</div>
<div class="clear"></div>
<div id="events">
<ul>    
<?php if (isset($events) && count($events)): ?>
    
    <?php foreach ($events as $event): ?>
        <li<?= ($first_new_event && ($event['id'] >= $first_new_event))? ' class="new_event"':'' ?>>
            <?php echo $event['date']; ?>: <?php echo $event['text']; ?><br/>
        </li>
    <?php endforeach; ?>
    
    <?php if ($pagination): ?>
        <?php if ($pagination['prev']): ?>
            <?php echo HTML::anchor('/events/p/'.$pagination['prev'], 'Poprzednia strona'); ?> &nbsp; &nbsp;
        <?php endif; ?>
        <?php if ($pagination['next']): ?>
            <?php echo HTML::anchor('/events/p/'.$pagination['next'], 'Następna strona'); ?>
        <?php endif; ?>
    <?php endif; ?>
                
<?php endif; ?>
</ul>
</div>