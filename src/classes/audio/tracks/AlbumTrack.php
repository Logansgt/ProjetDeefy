<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;

require_once 'vendor/autoload.php';

class AlbumTrack extends AudioTrack
{

    protected ?string $album;
    protected ?int $numero;
    protected ?string $artiste;

    public function __construct(string $titre, string $chemin, ?string $nom = null, ?int $numero = null, ?string $artiste = null)
    {
        parent::__construct($titre, $chemin);
        $this->album = $nom;
        $this->numero = $numero;
        $this->artiste = $artiste;
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }

}