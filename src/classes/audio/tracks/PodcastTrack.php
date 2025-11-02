<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
require_once 'vendor/autoload.php';

class PodcastTrack extends AudioTrack
{

    protected ?string $auteurPod;
    protected ?string $datePod;

    function __construct(string $t, string $chem, ?string $auteur = null, ?string $datePod = null)
    {
        parent::__construct($t, $chem);
        $this->auteurPod = $auteur;
        $this->datePod = $datePod;
    }

}