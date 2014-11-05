<?php
require '../../vendor/autoload.php';
use \ForceUTF8\Encoding;

$action = $_REQUEST['action'];

switch ($action) {
    case "upload":
        uploadCsv();
        break;
    case "loadAddresses":
        loadAddresses();
        break;
}

/**
 * strucutre CSV:
 * [0] => Nom
 * [1] => Description
 * [2] => Adresse
 * [3] => URL
 */
function uploadCsv()
{
    $pdo = dbConnect();
    $data = $_POST['data'];
    // Transformation des fins de ligne au format LF
    $data = str_replace("\r\n", PHP_EOL, $data);
    // détection du jeu de caractères
    $data = Encoding::fixUTF8($data);
    
    $data = explode(PHP_EOL, $data);
    $sql = "INSERT INTO address (title, description, address, url) 
            VALUES (:title, :desc,:address, :url)";
    
    $stm = $pdo->prepare($sql);
    $i = 0;
    foreach ($data as $line) {
        $entry = str_getcsv($line, ";");
        if (count($entry) != 4) {
            continue;
        }
        $stm->bindParam(':title', $entry[0]);
        $stm->bindParam(':desc', $entry[1]);
        $stm->bindParam(':address', $entry[2]);
        $stm->bindParam(':url', $entry[3]);
        try {
            $stm->execute();
            $i ++;
        } catch (Exception $e) {
            continue;
        }
    }
    echo $i;
}

function dbConnect()
{
    $dsn = 'mysql:dbname=project;hoste=localhost';
    $user = 'project';
    $password = '0000';
    
    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
   
    return $dbh;
}
function loadAddresses()
{
    
    $pdo=dbConnect();
    $sql="SELECT * FROM address ";
    $req = $pdo->query($sql);
    $result = $req->fetchAll();
    
    foreach($result as &$line) {
        $id= $line[0];
        $line[] = '<a href="#" data-id="' . $id . '" data-action="edit" >Editer</a>' .
                  ' - <a href="#" data-id="' . $id . '" data-action="delete">Supprimer</a>';
        array_shift($line);
    }
    
    $reponse= array('data'=>$result);
    echo json_encode($reponse);
    
}