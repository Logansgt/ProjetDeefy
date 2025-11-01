<?php

declare(strict_types = 1);
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\render\AlbumTrackRenderer;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\PodcastRenderer;
require_once 'vendor/autoload.php';

$A = new AlbumTrack("Piste1","https//Album1","PremierAlbum",1);
$AlbumRendu1 = new AlbumTrackRenderer($A);

$s = serialize($AlbumRendu1);

setcookie("track",$s,time()+60,"/");

if(isset ($_COOKIE["track"])){
    print($_COOKIE["track"]);
    echo (unserialize($_COOKIE["track"])->render(2));
}