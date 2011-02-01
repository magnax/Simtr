<div class="title_bar">Zdarzenia</div>
<div id="events">
<form action="u/event/talkall" method="POST">
    <input id="event_input_small" type="text" name="text"> <input type="submit" value="Talk to all">
</form>
<?php if (isset($events) && count($events)): ?>
    <?php foreach ($events as $event): ?>
        <?php echo Model_GameTime::formatDateTime($event['date']); ?>: <?php echo $event['text']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>
</div>