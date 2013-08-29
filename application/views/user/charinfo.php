<div class="title_bar">
    Profil postaci
</div>
<div id="userinfo">
    <table width="800">
        <tr>
            <td width="180">
               Nazwa:
            </td>
            <td width="420">
               <?php echo HTML::anchor('chname/'.$character['id'], $character['name']); ?>
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
                Dnia <?php echo Model_GameTime::formatDateTime($character['spawn_day'], 'd'); ?> w <?php echo HTML::anchor('lname/'.$character['spawn_location_id'], $character['spawn_location']); ?>
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
               <span id="location"><?= $character['location']['str']; ?></span>
            </td>
        </tr>
        <tr>
            <td>
                Projekt:
            </td>
            <td>
                <?php if (isset($character['project_id']) && $character['project_id']) : ?>
                    (<?php echo $character['project_name']; ?> <span id="project_percent"><?php echo $character['myproject']['percent']; ?></span>%)
                    <?php echo HTML::anchor('user/project/leave/'.$character['project_id'], '[Porzuć]'); ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div id="user_status">
    Food: <?php echo Utils::conditionBar($character['fed']); ?> Vitality: <?php echo Utils::conditionBar($character['life']); ?>
</div>
<div id="user_skills">
    Strength: <?php echo $character['strength']; ?>, Fighting: <?php echo $character['fighting']; ?>
</div>
<div id="actions">
    <ul>
        <li>
            <?php echo HTML::anchor('events', 'Zdarzenia'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/inventory', 'Inwentarz'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/location', 'Miejsce'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/building', 'Budynki i pojazdy'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/people', 'Ludzie'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/location/objects', 'Obiekty'); ?>
        </li>
        <li>
            <?php echo HTML::anchor('user/project', 'Projekty'); ?>
        </li>
    </ul>
</div>
<div class="clear"></div>