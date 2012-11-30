<div class="title_bar">
    <?php if ($note->id): ?>
        Edytuj notatkę
    <?php else: ?>
        Utwórz notatkę
    <?php endif; ?>
</div>
<?= form::open(); ?>
<?php if ($note->id): ?>
    <?= form::hidden('id', $note->id); ?>
<?php endif; ?>
Tytuł: <?= form::input('title', $note->title, array('size'=>40)); ?><br />
<?= form::textarea('text', $note->text); ?><br />
<?= form::label('editable', 'Edytowalna: '); ?>
<?= form::checkbox('editable', '1', (($note->id) ? !!$note->editable : true)); ?><br />
<?= form::submit('submit', 'Zapisz'); ?>
<?= form::close(); ?>