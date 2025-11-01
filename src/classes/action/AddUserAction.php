<?php
namespace iutnc\deefy\action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;
require_once 'vendor/autoload.php';

class AddUserAction extends Action{
    public function execute() : string{
        
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                return <<<Limite
                <form id="form-addUser" method="POST" action="?action=add-user">
                <label> Email
                <input type = "email" name = "mail" required>
                </label>
                <label> Mot de passe
                <input type = "password" name = "mdp" required>
                </label>
                <button type ="submit">S'inscrire</button>
                </form>
                Limite;
            }else{
                try{
                    if(AuthnProvider::register($_POST['mail'],$_POST['mdp'])){
                        return "Utilisateur crée et enregistré, vous pouvez maintenant vous connecter";
                    }
                }catch(AuthnException $e){
                    return "<p style='color:red;'>Erreur : {$e->getMessage()}</p>";
                }
            }
            return "Utilisateur non enregistré";   
        }
}