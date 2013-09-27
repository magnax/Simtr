<h2>Postacie (<?php echo $count; ?>)</h2>
<table>
    <thead>
        <th>
            ID
        </th>
        <th>
            Name (self)
        </th>
        <th>
            User
        </th>
        <th>
            Gender
        </th>
        <th>
            Current location
        </th>
        <th>
            Spawn location
        </th>
        <th>
            Created
        </th>
        <th>
            Life
        </th>
        <th>
            Fed
        </th>
        <th>
            Fighting
        </th>
        <th>
            Actions
        </th>
    </thead>
    <?php foreach ($characters as $character): ?>
        <tr>
            <td>
                <?php echo $character->id; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/characters/show/<?php echo $character->id; ?>" title="show all informations">
                    <?php echo $character->name; ?>
                </a>
            </td>
            <td>
                <?php echo $character->user->email; ?>
            </td>
            <td>
                <?php echo $character->sex; ?>
            </td>
            <td>
                <?php echo $character->location->name; ?> (<?php echo $character->location->id; ?>)
            </td>
            <td>
                <?php echo $character->spawn_location->name; ?> (<?php echo $character->spawn_location->id; ?>)
            </td>
            <td>
                <?php echo Model_GameTime::formatDateTime($character->created, "d"); ?>
            </td>
            <td>
                <?php echo $character->life ?>
            </td>
            <td>
                <?php echo $character->fed; ?>
            </td>
            <td>
                <?php echo $character->fighting; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/characters/edit/<?php echo $character->id; ?>">Edit</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>