<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AlbumTrack;
require_once 'vendor/autoload.php';


class AlbumTrackRenderer extends AudioTrackRenderer
{

    private AlbumTrack $Album;

    public function __construct(AlbumTrack $A)
    {
        $this->Album = $A;
    }

    protected function renderCompact(): string
    {
        return "<div class = 'track-compact'>
            <p>{$this->Album->numero}.{$this->Album->titre}</p>
            <audio controls src='{$this->Album->chemin}'></audio>
        </div>";
    }

    protected function renderLong(): string
    {
        return "<div class='track-long'>
            <h2>{$this->Album->titre}</h2>
            <p>Artiste : {$this->Album->auteur}</p>
            <p>Album : {$this->Album->album}</p>
            <p>Année : {$this->Album->date}</p>
            <p>Genre : {$this->Album->genre}</p>
            <audio controls src='{$this->Album->chemin}'></audio>
        </div>";
    }



}