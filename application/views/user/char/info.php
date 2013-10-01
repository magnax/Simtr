<div class="title_bar">Umiejętności</div>
<?php foreach ($skills as $skill): ?>
<div><?php echo $skill->skill->name; ?> - <?php echo Helper_View::FormatSkill($skill->level); ?></div>
<?php endforeach; ?>
<div  style="text-align: left;">
<p>Każda postać ma 15 umiejętności, które są potrzebne przy wykonywaniu różnych projektów.
Umiejętności można ćwiczyć. Na start dostaje się wszystkie umiejętności ustawione na
poziomie 0 ("niezręczny") i losowo rozdysponowane 5 punktów (kolejnych poziomów).<br />
Postać może uzyskać maksymalnie:
<ul style="text-align: left;">
    <li>
        15 punktów w ciągu pierwszych 60 lat życia (czyli licząc z początkowo wylosowanymi
        punktami, może mieć poziom mistrzowski w 5 umiejętnościach),
    </li>
    <li>
        16 punktów w ciągu kolejnych 40 lat (czyli łącznie może w tym wieku być
        mistrzem w 10 umiejętnościach),
    </li>
    <li>
        20 punktów w kolejnych 30 latach.
    </li>
</ul>
Czyli postać w wieku 150 lat może być mistrzem we wszystkich umiejętnościach.
Jednak są to wartości maksymalne - przy założeniu, że pracuje się 16 godzin na dobę.
Praca jest rozliczana dobowo i czas powyżej 16 godzin nie jest liczony. Za każdą dobę
otrzymuje się określoną ilość punktów za każdą umiejętność. Po przekroczeniu 
odpowiedniej ilości punktów zwiększany jest poziom danej umiejętności i kasowany
licznik punktów.
</div>