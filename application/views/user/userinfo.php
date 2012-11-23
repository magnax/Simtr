<div class="title_bar">Użytkownik</div>
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
                <?php if(!$user->active): ?>
                <a href="<?php echo URL::base(); ?>user/menu/resend_email">Ponownie wyślij aktywacyjnego e-maila</a>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>