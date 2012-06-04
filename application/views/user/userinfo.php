<div class="title_bar">User info</div>
<div id="userinfo">
    <table>
        <tr>
            <td>
               ID:
            </td>
            <td>
               <?php echo $user->getID(); ?>
            </td>
        </tr>
        <tr>
            <td>
                Nazwa:
            </td>
            <td>
               <?php echo $user->getFullName(); ?>
            </td>
        </tr>
        <tr>
            <td>
                Rok urodzenia:
            </td>
            <td>
                <?php echo $user->getBirthYear(); ?>
            </td>
        </tr>
        <tr>
            <td>
                E-mail:
            </td>
            <td>
               <?php echo $user->getEmail(); ?>
            </td>
        </tr>
        <tr>
            <td>
                Data rejestracji:
            </td>
            <td>
               <?php echo $user->getRegisterDate(); ?>
            </td>
        </tr>
        <tr>
            <td>
                Status:
            </td>
            <td>
               <?php echo $user->getStatus() ? 'Active' : 'Inactive'; ?>
            </td>
        </tr>
    </table>
    <form action="<?php echo url::site('user/profil'); ?>" method="GET">
        <input type="submit" value="Zmień">
    </form>
</div>