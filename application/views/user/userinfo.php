<div class="title_bar">User info</div>
<div id="userinfo">
    <table>
        <tr>
            <td>
               ID:
            </td>
            <td>
               <?php echo $user->id; ?>
            </td>
        </tr>
        <tr>
            <td>
                E-mail:
            </td>
            <td>
               <?php echo $user->email; ?>
            </td>
        </tr>
        <tr>
            <td>
                Data rejestracji:
            </td>
            <td>
               <?php echo $user->created; ?>
            </td>
        </tr>
        <tr>
            <td>
                Status:
            </td>
            <td>
               <?php echo $user->active ? 'Active' : 'Inactive'; ?>
            </td>
        </tr>
    </table>
    <form action="<?php echo url::site('user/profil'); ?>" method="GET">
        <input type="submit" value="Zmień">
    </form>
</div>