<?php
namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\PodcastRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
require_once 'vendor/autoload.php';

class SigninAction extends Action
{
    public function execute(): string
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        if (isset($_SESSION['playlist'])) {
            unset($_SESSION['playlist']);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return <<<Limite
                <form id="form-add" method="POST" action="?action=signin">
                <label> Email
                <input type = "email" name = "mail" placeholder="me@gmail.com" required>
                </label>
                <label> Mot de passe
                <input type = "password" name = "mdp" placeholder="Entrez votre mot de passe" required>
                </label>
                <button type ="submit">Connexion</button>
                </form>
            Limite;
        } else {
            $authn = new AuthnProvider();
            try {
                if ($authn::signin($_POST['mail'], $_POST['mdp']) === true) {
                    $_SESSION['user'] = $_POST['mail'];
                    return "<p style='color:green;'>Connexion réussie. Bienvenue {$_POST['mail']} !</p>";
                } else {
                    return "<p style='color:red;'>Email ou mot de passe incorrect.</p>";
                }
            } catch (AuthnException $e) {
                return "<p style='color:red;'>Erreur : {$e->getMessage()}</p>";
            }
        }
    }
}