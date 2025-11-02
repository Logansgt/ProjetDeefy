<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;
require_once 'vendor/autoload.php';

class AddUserAction extends Action
{
    public function execute(): string
    {

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            return <<<Limite
                <form id="form-addUser" method="POST" action="?action=add-user">
                <label class="orangeLabel"> Email
                <input type = "email" name = "mail" placeholder="me@gmail.com" required>
                </label>
                <label class="orangeLabel"> Mot de passe
                <input type = "password" name = "mdp" placeholder="Entrez votre mot de passe" required>
                </label>
                <button type ="submit">S'inscrire</button>
                </form>
                <p> Le mot de passe doit contenir : 1 digit, une minuscule/majuscule, un caractère spécial et au moins 10 caractères.</p>
                Limite;
        } else {
            try {
                if (AuthnProvider::register($_POST['mail'], $_POST['mdp'])) {
                    return "Utilisateur crée et enregistré, vous pouvez maintenant vous connecter";
                }
            } catch (AuthnException $e) {
                return "<p style='color:red;'>Erreur : {$e->getMessage()}</p>";
            }
        }
        return "Utilisateur non enregistré";
    }
}