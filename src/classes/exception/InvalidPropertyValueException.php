<?php

declare(strict_types = 1);
namespace iutnc\deefy\exception;

class InvalidPropertyValueException extends \Exception{


    public function __construct(String $mess){
        parent::__construct($mess);
    }

}