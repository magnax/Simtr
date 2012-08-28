<div class="title_bar">Zdarzenia</div>
<div id="talk_form">
    <?php echo form::open('#', array('id'=>'talk_all')); ?>
        <input id="event_input_small" type="text" name="text"> <input id="talk" type="submit" value="Talk to all">
    <?php echo form::close(); ?>
</div>
<div id="events">
    
<?php if (isset($events) && count($events)): ?>
    <ul>
    <?php foreach ($events as $event): ?>
        <li<?= (($event['id'] >= $first_new_event))? ' class="new_event"':'' ?>>
        <?php if ($event['date'] == ''): ?>
            <?php if ($event['prev']): ?>
                <?php echo html::anchor('/events/index/'.$event['prev'], 'Poprzednia strona'); ?> &nbsp; &nbsp;
            <?php endif; ?>
            <?php if ($event['next']): ?>
                <?php echo html::anchor('/events/index/'.$event['next'], 'Następna strona'); ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo $event['date']; ?>: <?php echo $event['text']; ?><br/>
        <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
</div>
<a id="insert" href="#">Insert</a>