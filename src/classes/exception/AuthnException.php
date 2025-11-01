<?php

declare(strict_types = 1);
namespace iutnc\deefy\exception;
require_once 'vendor/autoload.php';

class AuthnException extends \Exception{


    public function __construct(String $mess){
        parent::__construct($mess);
    }

}