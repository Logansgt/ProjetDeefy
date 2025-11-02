<?php


namespace iutnc\deefy\dispatch;
use iutnc\deefy\action as a;
require_once 'vendor/autoload.php';

class Dispatcher{

    private string $action;

    public function __construct(string $s){
        $this->action = $s;
    }

    public function run(){
        $protectedActions = ['playlist', 'add-playlist', 'add-track'];
        $html = "";
        if (in_array($this->action, $protectedActions) && !isset($_SESSION['user'])) {
            header('Location: ?action=signin');
            exit();
        }

        switch($this->action){
            case 'playlist':
                $html = (new a\DisplayPlaylistAction())->execute();
                break;
            case 'add-playlist':
                $html = (new a\AddPlaylistAction())->execute();
                break;
            case 'add-track':
                $html = (new a\AddPodcastTrackAction())->execute();
                break;
            case 'add-user':
                $html = (new a\AddUserAction())->execute();
                break;
            case 'signin':
                $html = (new a\SigninAction)->execute();
                break;
            case 'supprimer':
                $html = (new a\SupprimerTrackAction)->execute();
                break;
            default:
                $html = (new a\DefaultAction())->execute();
                break;
        }
        $this->renderPage($html);
    }


    public function renderPage(string $s): void{
        $menu = "";
        if (!isset($_SESSION['user'])) {
            $menu = <<<HTML
                <li><a href="?action=add-user">Créer un compte</a></li>
                <li><a href="?action=signin">Se connecter</a></li>
            HTML;
        }else{
            $menu = <<<HTML
                <li><a href=".">Se déconnecter</a></li>
                <li><a href="?action=playlist">Playlists</a></li>
                <li><a href="?action=add-playlist">Ajouter Playlist</a></li>
            HTML;
        }
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ma première page HTML</title>
        </head>
        <body>
            <ul>
                {$menu}
            </ul>
            {$s}
        </body>
        </html>
        HTML;
    }

}