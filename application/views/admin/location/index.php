<h2>Lokacje (<?php echo $locations_count; ?>)</h2>
<table>
    <thead>
        <th>
            ID
        </th>
        <th>
            Type
        </th>
        <th>
            Class
        </th>
        <th>
            Parent
        </th>
        <th>
            Name
        </th>
        <th>
            Actions
        </th>
    </thead>
    <?php foreach ($locations as $location): ?>
        <tr>
            <td>
                <?php echo $location->id; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/location/show/<?php echo $location->id; ?>" title="show all informations">
                    <?php echo $location->locationtype->name; ?>
                </a>
            </td>
            <td>
                <?php echo $location->class_id; ?> <?php echo $location->locationclass->name; ?>
            </td>
            <td>
                <?php echo ($location->parent_id) ? "{$location->parent->name} ({$location->parent->id})" : '-'; ?>
            </td>
            <td>
                <?php echo $location->name; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/location/edit/<?php echo $location->id; ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>