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

class AddPodcastTrackAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<p>Veuillez vous connecter</p>";
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<Limite
                <form id="form-add" method="POST" action="?action=add-track">
                    <h2> Champs Obligatoires :  </h2>
                    <ul>
                        <li><label> Titre de la piste
                            <input type = "text" name = "titre" placeholder="Entrez le titre" required>
                            </label>
                        </li>
                        <li>
                            <label> File name
                            <input type = "text" name = "chemin" placeholder="fichier.mp3" required >
                            </label>
                        </li>
                    </ul>
                    <h2> Champs Non Obligatoires :  </h2>
                    <ul>
                        <li>
                            <label> Genre
                            <input type = "text" name = "genre">
                            </label>
                        </li>
                        <li>
                            <label> Duree
                            <input type = "number" name = "duree">
                            </label>
                        </li>
                        <li>
                            <label> Type (A ou P)
                            <input type = "Char" name = "type">
                            </label>
                        </li>
                        <li>
                            <label> Artiste Album
                            <input type = "text" name = "ArtisteAlb">
                            </label>
                        </li>
                        <li>
                            <label> Titre Album
                            <input type = "text" name = "TitreAlb">
                            </label>
                        </li>
                        <li>
                            <label> Annee Album
                            <input type = "number" name = "AnneeAlb">
                            </label>
                        </li>
                        <li>
                            <label> Numéro Album
                            <input type = "number" name = "NumAlb">
                            </label>
                        </li>
                        <li>
                            <label> Auteur Podcast
                            <input type = "text" name = "AuteurPod">
                            </label>
                        </li>
                        <li>
                            <label> Date Podcast (YYYY-MM-DD)
                            <input type = "text" name = "DatePod">
                            </label>
                        </li>
                    </ul>
                    <button type="submit">Envoyer</button>
                </form>
            Limite;
        } else {

            // Paramètres obligatoires :

            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $chemin = filter_var($_POST['chemin'], FILTER_SANITIZE_URL);
            $webChemin = "src/classes/musiques/" . basename($chemin); // chemin jusqu'au fichier de musiques

            // Paramètres facultatifs :

            $genre = null;
            if (!empty($_POST['genre'])) {
                $genre = filter_var($_POST['genre'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $duree = null;
            if (!empty($_POST['duree'])) {
                $duree = filter_var($_POST['duree'], FILTER_VALIDATE_INT);
            }

            if ($duree === null) {
                $duree = 1; // Obligatoire pour le setter d'un AudioTrack
            }

            $type = null;
            if (!empty($_POST['type'])) {
                $type = filter_var($_POST['type'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if ($type !== 'A' && $type !== 'P') {
                $type = null;
            }

            $artisteAlb = null;
            if (!empty($_POST['ArtisteAlb'])) {
                $artisteAlb = filter_var($_POST['ArtisteAlb'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $titreAlb = null;
            if (!empty($_POST['TitreAlb'])) {
                $titreAlb = filter_var($_POST['TitreAlb'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $anneeAlb = null;
            if (!empty($_POST['AnneeAlb'])) {
                $anneeAlb = filter_var($_POST['AnneeAlb'], FILTER_VALIDATE_INT);
            }

            $numAlb = null;
            if (!empty($_POST['NumAlb'])) {
                $numAlb = filter_var($_POST['NumAlb'], FILTER_VALIDATE_INT);
            }

            $auteurPod = null;
            if (!empty($_POST['AuteurPod'])) {
                $auteurPod = filter_var($_POST['AuteurPod'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $datePod = null;
            if (!empty($_POST['DatePod'])) {
                $datePod = filter_var($_POST['DatePod'], FILTER_SANITIZE_SPECIAL_CHARS);
            }

            $r = DeefyRepository::getInstance();
            if (isset($_SESSION['playlist'])) {
                $IdPlaylist = $r->getIdPlaylistByTitle($_SESSION['playlist'], $r->getIdUser($_SESSION['user']));
                if ($type === 'A') {
                    $Piste = new AlbumTrack($titre, $webChemin, $titreAlb, $numAlb, $artisteAlb);
                } else {
                    if ($type === 'P') {
                        $Piste = new PodcastTrack($titre, $webChemin, $auteurPod, $datePod);
                    } else {
                        $Piste = new AudioTrack($titre, $webChemin);
                    }
                }
                if ($anneeAlb === null) {
                    $Piste->date = $anneeAlb;
                } else {
                    $Piste->date = (string) $anneeAlb;
                }
                $Piste->genre = $genre;
                try {
                    $Piste->duree = $duree;
                } catch (InvalidPropertyValueException $e) {
                    return $e->getmessage();
                }
                $r->ajouterTrack($Piste, $IdPlaylist);
                return "Track ajouté à la playlist {$_SESSION['playlist']}";
            }
            return "Echec de l'ajout";
        }
    }
}