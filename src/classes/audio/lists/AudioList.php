<?php
declare(strict_types = 1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\exception\InvalidPropertyNameException;
require_once 'vendor/autoload.php';

class AudioList{

    private int $id;
    private String $nom;
    private Int $nbPistes = 0;
    private Int $duree = 0;
    protected array $listeAudio;

    public function __construct(String $nom, array $tab = []){
        $this->nom = $nom;
        $this->listeAudio = $tab;
        foreach ($tab as $k -> $v){
            $this->duree += $v->duree;
        }
        $this->nbPistes = count($tab);
    }

    public function __get(String $att):mixed{
        try{
            if (property_exists($this, $att)) 
                return $this->$att;
            throw new InvalidPropertyNameException("$att: invalid property");
        }catch (InvalidPropertyNameException $e){
            print($e->getMessage());
        }
    }

    public function setID(int $i){
        $this->id = $i;
    }

    public function getID(): int{
        $this->id = $i;
    }

}
