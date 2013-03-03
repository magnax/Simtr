<style>
    td, th {
        border:1px solid #555;
        padding: 4px;
        margin: 0px;
    }
</style>
<table>
    <tr>
        <th>ID</th>
        <th>Nazwa</th>
        <th>Klucz</th>
        <th>Opis</th>
    </tr>
    <?php foreach ($projecttypes as $projecttype): ?>
    <tr>
        <td><?php echo $projecttype->id; ?></td>
        <td><?php echo html::anchor('admin/projecttypes/edit/'.$projecttype->id, $projecttype->name); ?></td>
        <td>&nbsp;<?php echo $projecttype->key; ?></td>
        <td>&nbsp;<?php echo $projecttype->description; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo html::anchor('admin/projecttypes/edit', 'Dodaj nowy typ projektu'); ?>