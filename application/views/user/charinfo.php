<div class="title_bar">
    Profil postaci
</div>
<div id="userinfo">
    <table width="600">
        <tr>
            <td width="180">
               Nazwa:
            </td>
            <td width="420">
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
                Day <?php echo Model_GameTime::formatDateTime($character['spawn_day'], 'd'); ?> w <?php echo html::anchor('lname?id='.$character['spawn_location_id'], $character['spawn_location']); ?>
            </td>
        </tr>
        <tr>
            <td>
                Dźwiga:
            </td>
            <td>
               <?php echo $character['eq_weight']; ?> gram
            </td>
        </tr>
        <tr>
            <td>
                Miejsce:
            </td>
            <td>
               <?php echo html::anchor('lname?id='.$character['location_id'], $character['location']); ?>
                <?php if ($character['sublocation']): ?>
                    <?php if ($character['sublocation_id']): ?>
                        : <?php echo html::anchor('lname?id='.$character['sublocation_id'], $character['sublocation']); ?>
                    <?php else: ?>
                        : <?= $character['sublocation']; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
                Projekt:
            </td>
            <td>
                <?php if (isset($character['project_id']) && $character['project_id']) : ?>
                    (<?php echo $character['myproject']['name']; ?> <span id="project_percent"><?php echo $character['myproject']['percent']; ?></span>%)
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
            <?php echo html::anchor('events', 'Zdarzenia'); ?>
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