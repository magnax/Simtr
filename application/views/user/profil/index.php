<div class="title_bar">Zmiana ustawień użytkownika</div>
<form action="<?php echo url::site('u/profil/save'); ?>" method="POST">
    <table>
        <tr>
            <td>
                Imię:
            </td>
            <td>
                <input type="text" name="firstname" value="<?php echo $user_data['firstname']; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Nazwisko:
            </td>
            <td>
                <input type="text" name="lastname" value="<?php echo $user_data['lastname']; ?>">
            </td>
        </tr>
        <tr>
            <td>
                Data urodzenia:
            </td>
            <td>
                <input type="text" name="birthdate" value="<?php echo date('Y-m-d', $user_data['birthdate']); ?>">
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" value="Zmień">
            </td>
        </tr>
    </table>
</form>