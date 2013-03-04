<div class="title_bar">
    <?php if ($note->id): ?>
        Edytuj notatkę
    <?php else: ?>
        Utwórz notatkę
    <?php endif; ?>
</div>
<?= Form::open(); ?>
<?php if ($note->id): ?>
    <?= Form::hidden('id', $note->id); ?>
<?php endif; ?>
Tytuł: <?= Form::input('title', $note->title, array('size'=>40)); ?><br />
<?= Form::textarea('text', $note->text); ?><br />
<?= Form::label('editable', 'Edytowalna: '); ?>
<?= Form::checkbox('editable', '1', (($note->id) ? !!$note->editable : true)); ?><br />
<?= Form::submit('submit', 'Zapisz'); ?>
<?= Form::close(); ?>