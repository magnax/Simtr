Date: <?php echo Model_GameTime::formatDateTime($game->getRawTime(), 'y/f (d)'); ?> Time: <?php echo $game->getTime(); ?><br />
There are 20 days in a year, 24 hours in a day, 60 minutes in an hour and
60 seconds in a minute.<br />
Active users in last 15 minutes: <?php echo $active_count; ?><br />