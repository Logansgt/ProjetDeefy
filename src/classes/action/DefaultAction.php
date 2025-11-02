<?php
namespace iutnc\deefy\action;


class DefaultAction extends Action
{

    public function execute(): string
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        return '<p>Veuillez vous connecter</p>';
    }
}
