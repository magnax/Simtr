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
        <th>Typ proj.</th>
        <th>Atak</th>
        <th>Punkty</th>
        <th>Rodzaj</th>
        <th>Produkcja</th>
    </tr>
    <?php foreach ($itemtypes as $itemtype): ?>
    <tr>
        <td><?php echo $itemtype->id; ?></td>
        <td><?php echo HTML::anchor('admin/itemtypes/edit/'.$itemtype->id, $itemtype->name); ?></td>
        <td><?php echo ($itemtype->projecttype->id) ? HTML::anchor('admin/projecttypes/edit/'.$itemtype->projecttype->id.'?redir=1', $itemtype->projecttype->name) : '-'; ?></td>
        <td>&nbsp;<?php echo $itemtype->attack; ?></td>
        <td>&nbsp;<?php echo $itemtype->points; ?></td>
        <td>&nbsp;<?php echo $itemtype->kind; ?></td>
        <td><a href="<?php echo URL::base(); ?>admin/specs/show/<?php echo $itemtype->id; ?>">prod.</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo HTML::anchor('admin/itemtypes/edit', 'Dodaj nowy typ przedmiotu'); ?>