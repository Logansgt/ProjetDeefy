<?php

declare(strict_types = 1);
use iutnc\deefy\audio\lists\Album;
use iutnc\deefy\audio\tracks\AlbumTrack;
require_once 'vendor/autoload.php';

$Piste1 = new AlbumTrack("Piste1","https//Album1","PremierAlbum",1);
$Piste2 = new AlbumTrack("Piste2","https//Album1","PremierAlbum",2);
$Piste3 = new AlbumTrack("Piste3","https//Album1","PremierAlbum",2);

$tab = array($Piste1,$Piste2);

$Alb = new Album("tonjoe","bob","jzeiogjoze",$tab);

$_SESSION["playlist"] = serialize($Alb);

$p = unserialize($_SESSION["playlist"]);

$p->ajouterPiste($Piste3);

$_SESSION["playlist"] = serialize($p);

echo $_SESSION["playlist"];