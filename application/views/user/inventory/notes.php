<?php echo View::factory('user/inventory/index')->render(); ?>
Notatki
<p>
<?php foreach ($notes as $note): ?>
    <?= html::anchor('user/notes/put/'.$note['id'], '[odłóż]'); ?>
    <?= html::anchor('user/notes/view/'.$note['id'], '[czytaj]'); ?>
    <?= html::anchor('user/notes/new/'.$note['id'], '[edytuj]'); ?>
    <?= $note['title']; ?><br />
<?php endforeach; ?>