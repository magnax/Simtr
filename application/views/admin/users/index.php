<h2>All users</h2>
<table>
    <thead>
        <th>
            ID
        </th>
        <th>
            E-mail
        </th>
        <th>
            Active
        </th>
        <th>
            Created
        </th>
        <th>
            Last logged
        </th>
        <th>
            Actions
        </th>
    </thead>
    <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <?php echo $user->id; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/users/show/<?php echo $user->id; ?>" title="show all informations">
                    <?php echo $user->email; ?>
                </a>
            </td>
            <td>
                <?php echo $user->active; ?>
            </td>
            <td>
                <?php echo $user->created; ?>
            </td>
            <td>
                <?php echo ($user->last_login) ? date("Y-m-d H:i", $user->last_login) : '-never-'; ?>
            </td>
            <td>
                <a href="<?php echo URL::base(); ?>admin/users/edit/<?php echo $user->id; ?>">Edit</a> :: 
                <a href="<?php echo URL::base(); ?>admin/characters/?user_id=<?php echo $user->id; ?>">Chars</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
