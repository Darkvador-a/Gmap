<?php

class CoordsService
{

    /**
     *
     * @var CoordsMapper
     */
    private $coordsMapper;

    /**
     *
     * @param CoordsMapper $coordsMapper            
     */
    public function __construct(CoordsMapper $coordsMapper)
    {
        $this->coordsMapper = $coordsMapper;
    }

    /**
     *
     * @param string $csv            
     * @return number
     */
    public function upload($csv)
    {
        $csv = str_replace("\r\n", PHP_EOL, $csv);
        $csv = explode(PHP_EOL, $csv);
        $i = 0;
        foreach ($csv as $line) {
            $entry = str_getcsv($line, ";");
            if (count($entry) != 4) {
                continue;
            }
            $coords = new Coords();
            $coords->setNom($entry[0])
                ->setDescription($entry[1])
                ->setAdress($entry[2])
                ->setUrl($entry[3]);
            $this->coordsMapper->save($coords);
            $i ++;
        }
        return $i;
    }

    /**
     *
     * @return string
     */
    public function readAll()
    {
        $response = array(
            'data' => array()
        );
        $coordsSet = $this->coordsMapper->fetchAll();
        
        foreach ($coordsSet as $coords) {
            $id = $coords->getId();
            $response['data'][] = array(
                $coords->getNom(),
                $coords->getDescription(),
                $coords->getAdress(),
                $coords->getUrl(),
                '<a href="#" data-id="' . $id . '" data-action="edit"><i class="glyphicon glyphicon-pencil"></i></a> ' .
                 ' <a href="#" data-id="' . $id . '" data-action="delete"><i class="glyphicon glyphicon-remove-circle"></i></a>'
            );
        }
        
        return json_encode($response);
    }

    /**
     *
     * @param number $id            
     * @return string
     */
    public function readById($id)
    {
        $coords = $this->coordsMapper->find($id);
        $result = array(
            'coords_id' => $coords->getId(),
            'coords_nom' => $coords->getNom(),
            'coords_desc' => $coords->getDescription(),
            'coords_adresse' => $coords->getAdress(),
            'coords_url' => $coords->getUrl()
        );
        return json_encode($result);
    }
    /**
     * 
     * @param string $nom
     * @param string $desc
     * @param string $adresse
     * @param string $url
     * @param number $id
     * @return boolean
     */
    public function save($nom, $desc, $adresse, $url, $id = 0)
    {
        $coords = new Coords();
        if(0!== (int) $id){
            $coords->setId($id);
        }
        $coords->setAdress($adresse)
                ->setDescription($desc)
                ->setNom($nom)
                ->setUrl($url);
        return $this->coordsMapper->save($coords);
    }
    /**
     * 
     * @param number $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->coordsMapper->delete($id);
    }
}