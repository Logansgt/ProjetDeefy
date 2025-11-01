<?php

declare(strict_types = 1);
namespace iutnc\deefy\auth;
require_once 'vendor/autoload.php';
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;
class AuthnProvider{

 static function signin(string $email, string $mdp):bool{
    try{
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw new AuthnException("Email invalide");
        }
        $r = DeefyRepository::getInstance();
            $hash = $r->getHashUser($email);
        if ($hash === null) {
            throw new AuthnException("Utilisateur inconnu");
        }
    }catch(AuthnException $e){
         return false;
    }
    if(!password_verify($mdp,$hash)){
        return false;
    }
    return $r->userExistant($email);
 }

 public static function register(string $email, string $mdp): bool {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Email invalide");
        }
        $r = DeefyRepository::getInstance();
        if (!$r->checkPasswordStrength($mdp,10)) {
            throw new AuthnException("Le mot de passe doit contenir : 1 digit, une minuscule/majuscule, un caractère spécial et 10 caractères");
        }
        $hash = $r->getHashUser($email);
        if ($hash !== null) {
            throw new AuthnException("Utilisateur déjà existant");
        }
        $r->addUser($email, $mdp);
        return true;
    }

}