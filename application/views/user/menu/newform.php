
<div class="title_bar">Utwórz nową postać</div>
<form action="<?php echo URL::site('u/menu/new'); ?>" method="POST">
    Imię: <input type="text" name="name"><br />
    Płeć: <select name="sex">
        <option value="0">-- wybierz --</option>
        <option value="K">Kobieta</option>
        <option value="M">Mężczyzna</option>
    </select><br />
    <input type="submit" value="Utwórz">
</form>