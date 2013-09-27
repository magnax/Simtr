<h2>Typy przedmiotów</h2>
<table>
    <thead>
        <th>
            ID
        </th>
        <th>
            Nazwa
        </th>
        <th>
            Typ proj.
        </th>
        <th>
            Atak
        </th>
        <th>
            Obrona
        </th>
        <th>
            Punkty
        </th>
        <th>
            Waga
        </th>
        <th>
            Rodzaj
        </th>
        <th>
            Akcje
        </th>
    </thead>
    <?php foreach ($itemtypes as $itemtype): ?>
    <tr>
        <td>
            <?php echo $itemtype->id; ?>
        </td>
        <td>
            <?php echo $itemtype->name; ?>
        </td>
        <td>
            <?php echo ($itemtype->projecttype->id) ? HTML::anchor('admin/projecttypes/edit/'.$itemtype->projecttype->id, $itemtype->projecttype->name) : '-'; ?>
        </td>
        <td>
            &nbsp;<?php echo $itemtype->attack; ?>
        </td>
        <td>
            &nbsp;<?php echo $itemtype->shield; ?>
        </td>
        <td>
            &nbsp;<?php echo $itemtype->points; ?>
        </td>
        <td>
            &nbsp;<?php echo $itemtype->weight; ?>
        </td>
        <td>
            &nbsp;<?php echo $itemtype->kind; ?>
        </td>
        <td>
            <a href="<?php echo URL::base(); ?>admin/specs/show/<?php echo $itemtype->id; ?>">
                Produkcja
            </a> 
            : 
            <a href="<?php echo URL::base(); ?>admin/itemtypes/edit/<?php echo $itemtype->id; ?>">
                Edycja
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo HTML::anchor('admin/itemtypes/edit', 'Dodaj nowy typ przedmiotu'); ?>