<?php
namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\repository\DeefyRepository;
require_once 'vendor/autoload.php';

class AddPlaylistAction extends Action{
    public function execute() : string{
        if(!isset($_SESSION['user'])){
            return "Veuillez vous connecter";
        }
        if ($_SERVER['REQUEST_METHOD']=== 'GET'){
            return <<<Limite
                <form id="form-add" method="POST" action="?action=add-playlist">
                <label> Nom de la playlist
                <input type = "text" name = "nom" placeholder="Entrez le titre" required>
                </label>
                </form>
            Limite;
            } 
            else{
                $nom = filter_var($_POST['nom'],FILTER_SANITIZE_SPECIAL_CHARS);
                $pl= new Playlist($nom);
                $r = DeefyRepository::getInstance();
                $idUser = $r->getIdUser($_SESSION['user']);
                $r->saveEmptyPlaylist($pl,$idUser);
                unset($_SESSION['playlist']);
                $_SESSION['playlist'] = $nom;
                return'Playlist crée, avec le nom : '.$_POST['nom'];
            }
        } 
    }
