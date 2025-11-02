<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AlbumTrack;
require_once 'vendor/autoload.php';

class Album extends AudioList
{

    private string $artiste;
    private string $dateSortie;

    public function __construct(string $nom, string $artiste, string $dateSortie, array $tab = [])
    {
        parent::__construct($nom, $tab);
        $this->artiste = $artiste;
        $this->dateSortie = $dateSortie;
    }

    public function __get(string $att): mixed
    {
        if (property_exists($this, $att)) {
            return $this->$att;
        }
        return parent::__get($att);
    }

    public function setArtiste(string $artiste): void
    {
        $this->artiste = $artiste;
    }

    public function ajouterPiste(AlbumTrack $a)
    {
        $this->listeAudio[] = $a;
        $this->duree += $a->duree;
        $this->nbPistes += 1;
    }

    public function setDateSortie(string $dateSortie): void
    {
        $this->dateSortie = $dateSortie;
    }

}