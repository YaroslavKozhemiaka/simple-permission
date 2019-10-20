<?php

namespace SimplePermission\Exceptions;

use Exception;

class AlreadyExistNameException extends Exception
{
    public function errorMessage() {
        //error message
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .' : Permission name \''.$this->getMessage().'\' is already exist.';
        return $errorMsg;
    }
}