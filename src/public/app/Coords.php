<?php
require '../../vendor/autoload.php';
use \ForceUTF8\Encoding;


class Coords
{

    /**
     *
     * @var int auto-incrémente
     */
    private $coords_id;

    /**
     *
     * @var string
     */
    private $coords_nom;

    /**
     *
     * @var string
     */
    private $coords_description;

    /**
     *
     * @var string
     */
    private $coords_addresse;

    /**
     *
     * @var string
     */
    private $coords_url;

    /**
     *
     * @var unknown
     */
    private $db;

    
    /**
     * Initial connection pdo
     */
    function __construct()
    {
        $this->setDb(dbConnect());   
    }

    

    /**
     * Import CSV
     */
    function importCSV()
    {
        $pdo = $this->db;
        $data = $_POST['data'];
        // Transformation des fins de ligne au format LF
        $data = str_replace("\r\n", PHP_EOL, $data);
        // détection du jeu de caractères
        $data = Encoding::fixUTF8($data);
        
        $data = explode(PHP_EOL, $data);
        $sql = "INSERT INTO coords
           (coords_nom, coords_desc, coords_adresse, coords_url)
           VALUES (:nom, :desc, :adresse, :url)";
        $stm = $pdo->prepare($sql);
        $i = 0;
        foreach($data as $line) {
            $entry = str_getcsv($line, ";");
            if(count($entry) != 4) {
                continue;
            }
            $stm->bindParam(':nom', $entry[0]);
            $stm->bindParam(':desc', $entry[1]);
            $stm->bindParam(':adresse', $entry[2]);
            $stm->bindParam(':url', $entry[3]);
            try {
                $stm->execute();
                $i++;
            } catch(Exception $e) {
                continue;
            }
        }
        echo $i;
    }
    /**
     * New coords
     */
	function newCoords()
    {
        $data['id'] = (int) $_POST['id'];
        $data['nom'] = (string) $_POST['nom'];
        $data['desc'] = (string) $_POST['description'];
        $data['adresse'] = (string) $_POST['adresse'];
        $data['url'] = (string) $_POST['url'];
         
        $pdo=$this->db;
        
        if ($data['id'] === 0) {
            $sql = "INSERT INTO coords
               (coords_nom, coords_desc, coords_adresse, coords_url)
               VALUES (:nom, :desc, :adresse, :url)";
            $stm = $pdo->prepare($sql);
        
            unset($data['id']);
            try {
                $stm->execute($data);
                echo 'saved';
            } catch(Exception $e) {
                echo $e->getMessage();
                exit;
            }
        } else {
            $sql = "UPDATE coords
               SET coords_nom = :nom,
                   coords_desc = :desc,
                   coords_adresse = :adresse,
                   coords_url = :url
               WHERE coords_id = :id";
            $stm = $pdo->prepare($sql);
            $stm->execute($data);
        }
           
    }
    /**
     * 
     */
    function readCoords()
    {
        $pdo=$this->db;
        $sql = "SELECT *
            FROM coords
            WHERE 1";
        $req = $pdo->query($sql);
        $result = $req->fetchAll();
    
        foreach($result as &$line) {
            $id = $line[0];
            $line[] = '<a href="#" data-id="' . $id . '" data-action="edit"><i class="glyphicon glyphicon-pencil"></i></a>' .
                ' <a href="#" data-id="' . $id . '" data-action="delete"><i class="glyphicon glyphicon-remove-circle"></i></a>';
            array_shift($line);
        }
    
        $response = array('data' => $result);
        echo json_encode($response);
    }
    /**
     * 
     * @param unknown $id
     */
    function readById($id)
    {

        $pdo=$this->db;
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $sql = "SELECT *
            FROM coords
            WHERE coords_id = :id";
        $stm = $pdo->prepare($sql);
        $stm->bindParam(':id', $id);
        $req = $stm->execute();
        $result = $stm->fetch();
        echo json_encode($result);
    }
    /**
     * 
     * @param index $id
     */
    function deleteCoords($id)
    {
        $pdo=$this->db;
        $sql = "DELETE FROM coords WHERE coords_id = ?";
        $stm = $pdo->prepare($sql);
        echo $stm->execute(array($id));
    }
    /**
     * 
     * @return PDO
     */
    function dbConnect()
    {
        $dsn = "mysql:dbname=project;host=localhost";
        $username = "project";
        $password = "0000";
        $pdo = new PDO($dsn, $username, $password, array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"
        ));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
        return $pdo;
    }

    /**
     *
     * @return the $coords_id
     */
    public function getCoords_id()
    {
        return $this->coords_id;
    }

    /**
     *
     * @return the $coords_nom
     */
    public function getCoords_nom()
    {
        return $this->coords_nom;
    }

    /**
     *
     * @return the $coords_description
     */
    public function getCoords_description()
    {
        return $this->coords_description;
    }

    /**
     *
     * @return the $coords_addresse
     */
    public function getCoords_addresse()
    {
        return $this->coords_addresse;
    }

    /**
     *
     * @return the $coords_url
     */
    public function getCoords_url()
    {
        return $this->coords_url;
    }
    /**
     * @return the $db
     */
    public function getDb()
    {
        return $this->db;
    }
    

    /**
     *
     * @param number $coords_id            
     */
    public function setCoords_id($coords_id)
    {
        $this->coords_id = $coords_id;
    }

    /**
     *
     * @param string $coords_nom            
     */
    public function setCoords_nom($coords_nom)
    {
        $this->coords_nom = $coords_nom;
    }

    /**
     *
     * @param string $coords_description            
     */
    public function setCoords_description($coords_description)
    {
        $this->coords_description = $coords_description;
    }

    /**
     *
     * @param string $coords_addresse            
     */
    public function setCoords_addresse($coords_addresse)
    {
        $this->coords_addresse = $coords_addresse;
    }

    /**
     *
     * @param string $coords_url            
     */
    public function setCoords_url($coords_url)
    {
        $this->coords_url = $coords_url;
    }
    /**
     * @param unknown $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }
}