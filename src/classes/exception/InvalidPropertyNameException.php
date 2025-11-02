<?php

declare(strict_types=1);
namespace iutnc\deefy\exception;

class InvalidPropertyNameException extends \Exception
{


    public function __construct(string $mess)
    {
        parent::__construct($mess);
    }

}