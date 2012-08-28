Miasta
<? foreach($towns as $town): ?>
    <p><?= html::anchor('admin/towns/edit?id='.$town->id, $town->location->name); ?>
<? endforeach; ?>
<p><?= html::anchor('admin/towns/edit', 'Dodaj miasto'); ?>