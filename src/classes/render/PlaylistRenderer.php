<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\lists\Playlist;
require_once 'vendor/autoload.php';

class PlaylistRenderer
{

    private Playlist $playlist;

    public function __construct(Playlist $pl)
    {
        $this->playlist = $pl;
    }

    public function renderPlaylist(int $selector)
    {
        foreach ($this->playlist->listeAudio as $v) {
            if ($v instanceof AlbumTrack) {
                $ar = new AlbumTrackRenderer($v);
                $ar->render($selector);
            } else {
                $pr = new PodcastRenderer($v);
                $pr->render($selector);
            }
        }
    }
}
