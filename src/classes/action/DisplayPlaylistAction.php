<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\render\AlbumTrackRenderer;
use iutnc\deefy\repository\DeefyRepository;
require_once 'vendor/autoload.php';

class DisplayPlaylistAction extends Action
{

    public function execute(): string
    {

        if (!isset($_SESSION['user'])) {
            return "<p>Veuillez vous connecter</p>";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_id'])) {
            $r = DeefyRepository::getInstance();
            $_SESSION['playlist_id'] = $_POST['playlist_id'];
            $_SESSION['playlist'] = $r->trouverPlaylist($_SESSION['playlist_id']);
        }

        if (isset($_SESSION['playlist'])) {
            $r = DeefyRepository::getInstance();
            $idUser = $r->getIdUser($_SESSION['user']);
            $pl = $r->reconstituerPlaylist($_SESSION['playlist'], $idUser);
            $resultHtml = "Playlist {$_SESSION['playlist']} :";
            foreach ($pl->listeAudio as $k => $v) {
                if ($v instanceof AlbumTrack) {
                    $rend = new AlbumTrackRenderer($v);
                } else {
                    $rend = new PodcastRenderer($v);
                }
                $trackId = $v->id;
                $resultHtml = $resultHtml . $rend->render(1);
                $resultHtml = $resultHtml . <<<HTML
                <p></p>
                <form method="POST" action="?action=supprimer" style="display:inline">
                <input type="hidden" name="track_id" value="{$trackId}">
                <button type="submit">Supprimer</button>
                </form>
            HTML;
            }
            return <<<HTML
                <li><a href="?action=add-track">Ajouter Track</a></li>
                <p></p>
                HTML . $r->listerPlaylistUser($idUser) . "<p></p>" . $resultHtml;
        } else {
            $r = DeefyRepository::getInstance();
            $idUser = $r->getIdUser($_SESSION['user']);
            $res = $r->listerPlaylistUser($idUser);
            if ($res === null) {
                return "Aucune playlist enregistrée";
            } else {
                return $res;
            }
        }
    }
}