<?php
class CainiaoException extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
