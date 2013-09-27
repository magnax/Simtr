<?php 
return array(
    'password' => array(
        'not_empty' => 'Hasło nie może być puste',
        'min_length' => 'Hasło musi mieć co najmniej 8 znaków',
    ),
    'password_confirm' => array(
        'not_empty' => 'Powtórzone hasło nie może być puste',
        'matches' => 'Wpisz dwa razy to samo hasło',
    ),
    'rule_agreement' => array(
        'not_empty' => 'Zaznacz, że przeczytałeś zasady gry'
    ),
);

?>