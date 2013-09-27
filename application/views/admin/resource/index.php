<h2>Materiały</h2>
<table>
    <thead>
        <th>ID</th>
        <th>Nazwa</th>
        <th>Raw?</th>
        <th>Dz. uzysk</th>
        <th>Dopełniacz</th>
    </thead>
    <?php foreach ($resources as $res): ?>
    <tr>
        <td><?php echo $res->id; ?></td>
        <td><?php echo HTML::anchor('admin/resource/edit/'.$res->id, $res->name); ?></td>
        <td><?php echo $res->is_raw ? 'TAK' : '-'; ?></td>
        <td>&nbsp;<?php echo $res->gather_base; ?></td>
        <td><?php echo $res->d; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo HTML::anchor('admin/resource/edit', 'Dodaj nowy surowiec'); ?>

