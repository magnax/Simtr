Miasta
<? foreach($towns as $town): ?>
    <p><?= HTML::anchor('admin/towns/edit?id='.$town->id, $town->location->name); ?>
<? endforeach; ?>
<p><?= HTML::anchor('admin/towns/edit', 'Dodaj miasto'); ?>