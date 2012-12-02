<div class="list">
    <div class="objects-menu">Notatki</div>  
<?php foreach ($notes as $note): ?>
    <div>
        <a href="/user/notes/put/<?=$note->id; ?>" title="Odłóż notatkę">
            <img src="/assets/images/drop.png" height=32 width=32>
        </a>
        <a href="/user/notes/copy/<?=$note->id; ?>" title="Kopiuj notatkę">
            <img src="/assets/images/copy.png" height=32 width=32>
        </a>
        <a href="/user/notes/view/<?=$note->id; ?>" title="Czytaj notatkę">
            <img src="/assets/images/view.png" height=32 width=32>
        </a>
    <? if ($note->editable || ($note->created_by == $character['id'])): ?>
        <?= html::anchor('user/notes/new/'.$note->id, '[edytuj]'); ?>
    <? endif; ?>
    <?= $note->title; ?>
    <? if ($note->created_by == $character['id']): ?>
        (moja)
    <? endif; ?>
    </div>
<?php endforeach; ?>
</div>  