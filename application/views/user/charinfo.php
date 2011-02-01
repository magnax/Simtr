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
               <?php echo html::anchor('u/char/nameform/'.$character['id'], $character['known_as']); ?>
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
                <?php echo $character['spawn_day']; ?> w <?php echo html::anchor('u/location/nameform/'.$character['spawn_location_id'], $character['spawn_location']); ?>
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
               <?php echo html::anchor('u/location/nameform/'.$character['location_id'], $character['location']); ?>
            </td>
        </tr>
        <tr>
            <td>
                Projekt:
            </td>
            <td>
                <?php if (isset($character['project_id'])) : ?>
                    (<?php echo $character['myproject']['name']; ?> <?php echo $character['myproject']['percent']; ?>%)
                    <?php echo html::anchor('u/project/leave/'.$character['project_id'], '[Porzuć]'); ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>
<div id="actions">
    <ul>
        <li>
            <?php echo html::anchor('events', 'Zdarzenia'); ?>
        </li>
        <li>
            <?php echo html::anchor('inventory', 'Inwentarz'); ?>
        </li>
        <li>
            <?php echo html::anchor('location', 'Miejsce'); ?>
        </li>
        <li>
            <?php echo html::anchor('buildings', 'Budynki i pojazdy'); ?>
        </li>
        <li>
            <?php echo html::anchor('people', 'Ludzie'); ?>
        </li>
        <li>
            <?php echo html::anchor('objects', 'Obiekty'); ?>
        </li>
        <li>
            <?php echo html::anchor('projects', 'Projekty'); ?>
        </li>
    </ul>
</div>
<div class="clear"></div>