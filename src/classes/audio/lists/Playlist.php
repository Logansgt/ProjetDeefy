<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AudioTrack;
require_once 'vendor/autoload.php';

class Playlist extends AudioList
{

    public function __construct(string $nom, array $tab = [])
    {
        parent::__construct($nom, $tab);
    }

    public function ajouterPiste(AudioTrack $a)
    {
        $this->listeAudio[] = $a;
        $this->duree += $a->duree;
        $this->nbPistes += 1;
    }

    public function SupprimerPiste(AudioTrack $a)
    {
        foreach ($this->listeAudio as $k->$v) {
            if ($v === $a) {
                unset($this->listeAudio[$k]);
                $this->listeAudio = array_values($this->listeAudio);
            }
        }
    }

    public function ajouterTabPiste(array $a)
    {
        foreach ($a as $pistes) {
            $this->ajouterPiste($pistes);
        }
    }

}