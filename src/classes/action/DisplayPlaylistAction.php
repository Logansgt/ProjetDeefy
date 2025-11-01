<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\repository\DeefyRepository;
require_once 'vendor/autoload.php';

class DisplayPlaylistAction extends Action{

    public function execute(): string{

        if(!isset($_SESSION['user'])){
            return "Veuillez vous connecter";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_id'])) {
            $r = DeefyRepository::getInstance();
            $_SESSION['playlist_id'] = $_POST['playlist_id'];
            $_SESSION['playlist'] = $r->trouverPlaylist($_SESSION['playlist_id']);
        }

        if (isset($_SESSION['playlist'])){
            $r = DeefyRepository::getInstance();
            $pl = $r->reconstituerPlaylist($_SESSION['playlist']);
            $resultHtml = "Playlist {$_SESSION['playlist']} :";
            foreach($pl->listeAudio as $k=>$v){
                $pr = new PodcastRenderer($v);
                $resultHtml = $resultHtml.$pr->render(1);
            }
                $idUser = $r->getIdUser($_SESSION['user']);
                return <<<HTML
                <li><a href="?action=add-track">Ajouter Track</a></li>
                <p></p>
                HTML.$r->listerPlaylistUser($idUser)."<p></p>".$resultHtml;
        }else{
            $r = DeefyRepository::getInstance();
            $idUser = $r->getIdUser($_SESSION['user']);
            $res = $r->listerPlaylistUser($idUser);
            if($res === null){
                return "Aucune playlist enregistrée";
            }
            else{
                return $res;
            }
        }
    }
}