<?php
namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\InvalidPropertyValueException;

require_once 'vendor/autoload.php';

class SupprimerTrackAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<p>Veuillez vous connecter</p>";
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_id']) && isset($_SESSION['playlist'])) {
            $r = DeefyRepository::getInstance();
            $idPlaylist = $r->getIdPlaylistByTitle($_SESSION['playlist'], $r->getIdUser($_SESSION['user']));
            $idTrack = $_POST['track_id'];
            $r->supprimerTrack($idTrack, $idPlaylist);
            header('Location: ?action=playlist');
            exit();
        }
    }
}