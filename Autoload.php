<?php

declare(strict_types = 1);

class Autoload{

    private String $racine;
    private String $prefixe;


    public function __construct(String $r, String $p){

        $this ->racine = $r;
        $this ->prefixe = $p;

    }

    public function loadClass(String $className){

        $className.str_replace($this->prefixe, $this->racine);
        $className.str_replace("\\","/");    
        
        if(is_file($className)){
            require_once($className);
        }
           
    }

    public function register(){
        return spl_autoload_register($this,'loadClass');
    }




}