<?php
require_once 'app/Coords.php';

class CoordsMapper
{

    /**
     *
     * @var PDO
     */
    private $dbAdapter;

    /**
     *
     * @param PDO $db            
     */
    public function __construct($db)
    {
        $this->dbAdapter = $db->getConnexion();
    }

    /**
     *
     * @param number $id            
     * @return Coords
     */
    public function find($id)
    {
        $this->dbAdapter->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM coords   WHERE coords_id = :id";
        $stm = $this->dbAdapter->prepare($sql);
        $stm->bindParam(':id', $id);
        $req = $stm->execute();
        $row = $stm->fetch();
        
        if (! $row)
            return false;
        
        return $this->rowToObject($row);
    }

    /**
     *
     * @return multitype:Coords
     */
    public function fetchAll()
    {

        $this->dbAdapter->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $sql = "SELECT * FROM coords  WHERE 1";
        $stm = $this->dbAdapter->query($sql);
        $rowSet = $stm->fetchAll();
  
        
        $coordsAll = array();
        foreach ($rowSet as $row) {
            $coordsAll[] = $this->rowToObject($row);
           
        }
       
        return $coordsAll;
    }
    /**
     * 
     * @param Coords $coords
     * @return boolean
     */
    public function save(Coords $coords) // type hinting/ typage objet
    {
        // soit null, zero, vide
        // Nouvelle enregistrement ?
        if (0 === (int) $coords->getId()) {
            $sql = "INSERT INTO coords
               (coords_nom, coords_desc, coords_adresse, coords_url)
               VALUES (:coords_nom, :coords_desc, :coords_adresse, :coords_url)";
            // ou enregistrement exsitant
        } else {
            $sql = "UPDATE coords  
                    SET 
                       coords_nom = :coords_nom,
                       coords_desc = :coords_desc,
                       coords_adresse = :coords_adresse,
                       coords_url = :coords_url
                   WHERE 
                        coords_id = :coords_id";
        }
        $stm = $this->dbAdapter->prepare($sql);
        $row = $this->objectToRow($coords);
        return (bool) $stm->execute($row);
    }
    /**
     * 
     * @param number $id
     */
    public function delete($id)
    {
        $sql = "DELETE FROM coords WHERE coords_id = ?";
        $stm = $this->dbAdapter->prepare($sql);
        return $stm->execute(array($id));
    }
    
    /**
     *
     * @param multitype: array $row
     * @return Coords
     */
    private function rowToObject($row)
    {
        $coords = new Coords();
        $coords->setId($row['coords_id'])
            ->setNom($row['coords_nom'])
            ->setDescription($row['coords_desc'])
            ->setAdress($row['coords_adresse'])
            ->setUrl($row['coords_url']);
        
        return $coords;
    }

    /**
     *
     * @param Coords $coords            
     * @return multitype:Ambigous <the, string>
     */
    private function objectToRow(Coords $coords)
    {
        $row = array();
        if (0 !== (int) $coords->getId()) {
            $row['coords_id'] = $coords->getId();
        }
        $row['coords_nom'] = $coords->getNom();
        $row['coords_desc'] = $coords->getDescription();
        $row['coords_adresse'] = $coords->getAdress();
        $row['coords_url'] = $coords->getUrl();
        
        return $row;
    }
}