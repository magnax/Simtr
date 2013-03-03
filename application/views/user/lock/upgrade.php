Możesz rozbudować ten zamek do:

<?php echo Form::open(); ?>
<?php echo Form::hidden('lock_id', $lock->id); ?>
<?php foreach ($levels as $level): ?>
    <div>
        <?php echo Form::radio('level', $level->level); ?>
        <?php echo Form::label('level', $level->name); ?>
        <?php if (isset($specs[$level->itemtype_id]['specs'])): ?>
            <br />Czas potrzebny:<?=$specs[$level->itemtype_id]['specs']->time;?><br />
        <?php endif; ?>
        <?php if (isset($specs[$level->itemtype_id]['raws'])): ?>
            Materiały:<br />
            <?php foreach ($specs[$level->itemtype_id]['raws'] as $raw): ?>
                <?php echo $raw->resource->name; ?>: <?php echo $raw->amount;?> gram <br />
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
<?php echo Form::submit('upgrade', 'Wstaw zamek'); ?>
<?php echo Form::close(); ?>

INFO: będzie kilka poziomów zamków. Najprostszy (poziom 1) będzie drewniany (+ trochę żelaza na klucz).
Taki zamek można będzie w kilka godzin rozwalić (100% szans), ale tylko odpowiednim narzędziem (łom).
Zamek poziomu 2 będzie żelazny (wymagania: żelazo) i silniejszy - dłuższy czas rozwalania + mniejsze 
prawdopodobieństwo sukcesu (50%?). Zamek poziom 3 będzie zamkiem wzmocnionym - wymagania: żelazo i stal, czas rozwalania
bardzo długi (np. 2 dni) i jeszcze mniejsze prawd. sukcesu (np. 33%).
Ostatni poziom to ma być zamek pancerny ;) Bardzo duże wymagania (np. żelazo, stal, magnez, może jeszcze coś). 
Będzie albo nie do sforsowania, albo przy użyciu naprawdę dużych środków (dłubanie łomem odpada ;)).

