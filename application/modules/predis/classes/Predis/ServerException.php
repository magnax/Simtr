<?php

// Server-side errors
class Predis_ServerException extends PredisException {
    public function toResponseError() {
        return new Predis_ResponseError($this->getMessage());
    }
}

?>
