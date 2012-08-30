<div class="title_bar">
    Profil postaci
</div>
<div id="userinfo">
    <table>
        <tr>
            <td>
               Nazwa:
            </td>
            <td>
               <?php echo html::anchor('chname?id='.$character['id'], $character['name']); ?>
            </td>
        </tr>
        <tr>
            <td>
                Wiek:
            </td>
            <td>
               <?php echo $character['age']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Przebudzenie:
            </td>
            <td>
                <?php echo $character['spawn_day']; ?> w <?php echo html::anchor('lname?id='.$character['spawn_location_id'], $character['spawn_location']); ?>
            </td>
        </tr>
        <tr>
            <td>
                Dźwiga:
            </td>
            <td>
               <?php echo $character['eq_weight']; ?>g
            </td>
        </tr>
        <tr>
            <td>
                Miejsce:
            </td>
            <td>
               <?php echo html::anchor('lname?id='.$character['location_id'], $character['location']); ?>
            </td>
        </tr>
        <tr>
            <td>
                Projekt:
            </td>
            <td>
                <?php if (isset($character['project_id'])) : ?>
                    (<?php echo $character['myproject']['name']; ?> <?php echo $character['myproject']['percent']; ?>%)
                    <?php echo html::anchor('user/project/leave/'.$character['project_id'], '[Porzuć]'); ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div id="user_status">
    Food: 100, Vitality: <?php echo $character['life']; ?>/<?php echo $character['vitality']; ?>
</div>
<div id="user_skills">
    Strength: <?php echo $character['strength']; ?>, Fighting: <?php echo $character['fighting']; ?>
</div>
<div id="actions">
    <ul>
        <li>
            <?php echo html::anchor('user/event', 'Zdarzenia'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/inventory', 'Inwentarz'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/location', 'Miejsce'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/building', 'Budynki i pojazdy'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/people', 'Ludzie'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/location/objects', 'Obiekty'); ?>
        </li>
        <li>
            <?php echo html::anchor('user/project', 'Projekty'); ?>
        </li>
    </ul>
</div>
<div class="clear"></div>