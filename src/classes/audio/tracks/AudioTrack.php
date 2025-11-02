<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack
{

    protected int $id;
    protected string $titre;
    protected ?string $auteur = null;
    protected ?string $date = null;
    protected ?string $genre = null;
    protected ?int $duree = null;
    protected string $chemin;

    function __construct(string $titre, string $chemin)
    {
        $this->titre = $titre;
        $this->chemin = $chemin;
    }

    public function __get(string $att): mixed
    {
        try {
            if (property_exists($this, $att))
                return $this->$att;
            throw new InvalidPropertyNameException("$att: invalid property");
        } catch (InvalidPropertyNameException $e) {
            print ($e->getMessage());
        }
    }

    public function __set(string $att, mixed $value): void
    {
        if ($att === "duree") {
            if (!is_int($value) || $value <= 0) {
                throw new InvalidPropertyValueException("$att: invalid value");
            }
            $this->duree = $value;
        } elseif (property_exists($this, $att)) {
            $this->$att = $value;
        } else {
            throw new InvalidPropertyNameException("$att: invalid property");
        }
    }

}