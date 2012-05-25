<?php

interface Predis_Pipeline_IPipelineExecutor {
    public function execute(Predis_IConnection $connection, &$commands);
}

?>
