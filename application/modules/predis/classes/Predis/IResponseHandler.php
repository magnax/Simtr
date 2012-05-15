<?php

interface Predis_IResponseHandler {
    function handle(Predis_Connection $connection, $payload);
}


?>
