<?php
declare(strict_types = 1);
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\render\AlbumTrackRenderer;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\PodcastRenderer;
require_once 'vendor/autoload.php';

$Piste1 = new AlbumTrack("Piste1","https//Album1","PremierAlbum",1);
$Piste2 = new AlbumTrack("Piste2","https//Album1","PremierAlbum",2);

//print($Piste1);
//echo $Piste2->__toString();

$AlbumRendu1 = new AlbumTrackRenderer($Piste1);
$AlbumRendu2 = new AlbumTrackRenderer($Piste2);

echo $AlbumRendu1->render(1);
echo $AlbumRendu2->render(2);

$Podcast1 = new PodcastTrack("Pod1","https//Podcast1");
$Podcast2 = new PodcastTrack("Pod2","https//Podcast1");

$PodcastRendu1 = new PodcastRenderer($Podcast1);
$PodcastRendu2 = new PodcastRenderer($Podcast2);


echo $PodcastRendu1->render(1);
echo $PodcastRendu2->render(2);

