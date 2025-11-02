<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\PodcastTrack;
require_once 'vendor/autoload.php';


class PodcastRenderer extends AudioTrackRenderer
{

    private PodcastTrack $podcast;

    public function __construct(PodcastTrack $P)
    {
        $this->podcast = $P;
    }


    protected function renderCompact(): string
    {
        return "<div class = 'track-compact'>
            <p>{$this->podcast->titre}</p>
            <audio controls src='{$this->podcast->chemin}'></audio>
        </div>";
    }

    protected function renderLong(): string
    {
        return "<div class='track-long'>
            <h2>{$this->podcast->titre}</h2>
            <p>Artiste : {$this->podcast->auteur}</p>
            <p>Année : {$this->podcast->date}</p>
            <p>Genre : {$this->podcast->genre}</p>
            <audio controls src='{$this->podcast->chemin}'></audio>
        </div>";
    }
}