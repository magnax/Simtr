<h2>Typy przedmiotów</h2>
<table>
    <thead>
        <th>ID</th>
        <th>Nazwa</th>
        <th>Klucz</th>
        <th>Opis</th>
    </thead>
    <?php foreach ($projecttypes as $projecttype): ?>
    <tr>
        <td><?php echo $projecttype->id; ?></td>
        <td><?php echo HTML::anchor('admin/projecttypes/edit/'.$projecttype->id, $projecttype->name); ?></td>
        <td>&nbsp;<?php echo $projecttype->key; ?></td>
        <td>&nbsp;<?php echo $projecttype->description; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo HTML::anchor('admin/projecttypes/edit', 'Dodaj nowy typ projektu'); ?>