<?php

declare(strict_types = 1);

namespace iutnc\deefy\repository;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;

class DeefyRepository{

    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf) {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }

    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file) {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        $driver = $conf['driver'];
        $host = $conf['host'];
        $database = $conf['database'];
        self::$config = [ 'dsn'=> "$driver:host=$host;dbname=$database",'user'=> $conf['username'],'pass'=> $conf['password'] ];
    }

    // Partie Playlist

    public function saveEmptyPlaylist(Playlist $pl, int $idUser): Playlist {
        $sql = "INSERT INTO user2playlist VALUES (?,?)";
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
    
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pl->nom]);
        $lastId = (int)$this->pdo->lastInsertId();
        $pl->setID($lastId);

        $stmt2 = $this->pdo->prepare($sql);
        $stmt2->bindParam(1,$idUser);
        $stmt2->bindParam(2,$lastId);
        $stmt2-> execute();
        return $pl;
    }

    public function listerPlaylistUser(int $idUser): ?string{
        $sql = "SELECT p.nom,p.id FROM playlist p INNER JOIN user2playlist u ON p.id = u.id_pl WHERE u.id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1,$idUser);
        $stmt->execute();
        $res = $stmt->fetchALL(\PDO::FETCH_ASSOC);
        if($res === null || empty($res)){
            return null;
        }
        $resultHtml = "";
        foreach($res as $titres){
            $id = $titres['id'];
            $resultHtml = $resultHtml.<<<HTML
            <form method="POST" action="?action=playlist" style="display:inline">
            <input type="hidden" name="playlist_id" value="{$id}">
            <button type="submit">{$titres['nom']}</button>
            </form>
            HTML;
        }
        return $resultHtml;
        }

    public function trouverPlaylist(int $idPlay): ?string{
        $sql = "SELECT p.nom FROM playlist p WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1,$idPlay);
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $res['nom'];
    }

    public function reconstituerPlaylist(string $playlist): ?Playlist{
        $id = $this->getIdPlaylistByTitle($playlist);
        $sqlTrack = "SELECT id_track,no_piste_dans_liste FROM playlist2track WHERE id_pl = ?";
        $stmt2 = $this->pdo->prepare($sqlTrack);
        $stmt2->bindParam(1,$id);
        $stmt2->execute();
        $res2 = $stmt2->fetchALL(\PDO::FETCH_ASSOC);
        $tabConfig = [];
        if(empty($res2)){
            return new Playlist($playlist);
        }
        foreach($res2 as $track){
            $tabConfig[]=['id' => $track['id_track'],'numero' => $track['no_piste_dans_liste']];
        }
        usort($tabConfig, function($a, $b) {
            return $a['numero'] <=> $b['numero'];
        });
        $pl = new Playlist($playlist);
        $sqlPiste = "SELECT * FROM track WHERE id = ?";
        $stmt3 = $this->pdo->prepare($sqlPiste);
        foreach($tabConfig as $piste){
            $stmt3->bindParam(1,$piste['id']);
            $stmt3->execute();
            $res3 = $stmt3->fetch(\PDO::FETCH_ASSOC);
            if(!empty($res3)){
                if($res3['type']==="P"){
                    $pod = new PodcastTrack($res3['titre'],$res3['filename']);
                    $pl->ajouterPiste($pod);
                }else{
                    $tr = new PodcastTrack($res3['titre'],$res3['filename']);
                    $pl->ajouterPiste($tr);
                }
            }
        }
        return $pl;
    }

    public function getIdPlaylistByTitle(string $playlist): ?int{
        $sql = "SELECT id FROM playlist WHERE nom = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(1,$playlist);
        $stmt->execute();
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($res === null){
            return null;
        }
        return (int) $res['id'];
    }

    public function ajouterTrack(AudioTrack $at,int $idPlaylist): void{
        $sql = "INSERT INTO playlist2track VALUES (?,?,?)";
        $query = "INSERT INTO track (titre,genre,duree,filename,type,artiste_album,titre_album,annee_album,numero_album,auteur_podcast,date_posdcast) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $compteur = "SELECT COUNT(*) FROM playlist2track WHERE id_pl =?";
    
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(1,$at->titre);
        $stmt->bindValue(2,$at->genre);
        $stmt->bindValue(3,$at->duree);
        $stmt->bindValue(4,$at->chemin);
        if($at instanceof AlbumTrack){
            $stmt->bindValue(5,'A');
            $stmt->bindValue(6,$at->artiste);
            $stmt->bindValue(7,$at->album);
            $stmt->bindValue(8,$at->date);
            $stmt->bindValue(9,$at->numero);
            $stmt->bindValue(10,null);
            $stmt->bindValue(11,null);
        }else{
            if($at instanceof PodcastTrack){
                $stmt->bindValue(5,'P');
                $stmt->bindValue(6,null);
                $stmt->bindValue(7,null);
                $stmt->bindValue(8,null);
                $stmt->bindValue(9,null);
                $stmt->bindValue(10,$at->auteurPod);
                $stmt->bindValue(11,$at->datePod);
            }else{
                $stmt->bindValue(5,null);
                $stmt->bindValue(6,null);
                $stmt->bindValue(7,null);
                $stmt->bindValue(8,null);
                $stmt->bindValue(9,null);
                $stmt->bindValue(10,null);
                $stmt->bindValue(11,null);
            }
        }
        $stmt->execute();
        $lastId = (int)$this->pdo->lastInsertId();

        $stmtCompteur = $this->pdo->prepare($compteur);
        $stmtCompteur->bindParam(1,$idPlaylist);
        $stmtCompteur->execute();
        $nbTracks = $stmtCompteur->fetch(\PDO::FETCH_COLUMN);
        if($nbTracks === null){
            $nbTracks = 0;
        }
        $compteur = (int)$nbTracks+1;

        $stmt2 = $this->pdo->prepare($sql);
        $stmt2->bindParam(1,$idPlaylist);
        $stmt2->bindParam(2,$lastId);
        $stmt2->bindParam(3,$compteur);
        $stmt2-> execute();
    }

    // Partie Utilisateur

    public function addUser(string $email, string $mdp): void {
        $query = "INSERT INTO user(email,passwd) VALUES (:email,:mdp)";
        $stmt = $this->pdo->prepare($query);
        $newMdp = password_hash($mdp,PASSWORD_DEFAULT,['cost'=>12]);
        $stmt->execute(['email' => $email,'mdp'=>$newMdp]);
    }

    public function getHashUser(string $email): ?string{
        $query = "SELECT passwd FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $row['passwd'] : null;
    }

    public function getIdUser(string $email): ?int{
        $query = "SELECT id FROM user WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : null;
    }

    public function userExistant(string $email): bool{
        $repo = DeefyRepository::getInstance();
        $hash = $repo->getHashUser($email);
        if ($hash !== null) {
            return true;
        }
        return false;
    }

    public function checkPasswordStrength(string $pass, int $minimumLength): bool {
        $length = (strlen($pass) >= $minimumLength); // vrai si OK
        $digit = preg_match("#\d#", $pass) === 1; // au moins un chiffre
        $special = preg_match("#\W#", $pass) === 1; // au moins un caractère spécial
        $lower = preg_match("#[a-z]#", $pass) === 1; // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass) === 1; // au moins une majuscule
        return ($length && $digit && $special && $lower && $upper);
    }

}