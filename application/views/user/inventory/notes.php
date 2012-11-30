<?php echo View::factory('user/inventory/index')->render(); ?>
Notatki
<p>
<?php foreach ($notes as $note): ?>
    <?= html::anchor('user/notes/put/'.$note->id, '[odłóż]'); ?>
    <?= html::anchor('user/notes/copy/'.$note->id, '[kopiuj]'); ?>
    <?= html::anchor('user/notes/view/'.$note->id, '[czytaj]'); ?>
    <? if ($note->editable || ($note->created_by == $character['id'])): ?>
        <?= html::anchor('user/notes/new/'.$note->id, '[edytuj]'); ?>
    <? endif; ?>
    <?= $note->title; ?>
    <? if ($note->created_by == $character['id']): ?>
        (moja)
    <? endif; ?>
    <br />
<?php endforeach; ?>