<?php


class Db
{
   /**
    * 
    * @var string
    */
   private $driver;
   /**
    * 
    * @var string
    */
   private $host;
   /**
    * 
    * @var string
    */
   private $dbname;
   /**
    * 
    * @var string
    */
   private $username;
   /**
    * 
    * @var string
    */
   private $password;
  /**
   * 
   * @var number
   */ 
   private $port = 3306;
   /**
    * Permet de partager une mm ressource à tous les objets Db
    * @var array
    */
   static private $allowedDrivers=array('mysql');
   /**
    * 
    * @var PDO
    */
   private $connexion;
   
   public function __construct($driver, $host,$dbname, 
       $username, $password,$port=3306 )
   {
       //self:: permet de sélectionner la propriété $allowedDrivers
       // A la place du $this->
       if(!in_array($driver,self::$allowedDrivers )){
           throw new InvalidArgumentException("$driver is not a PDO driver valid !");
       }
       
       // Test si l'address ip est valide ou c'est sous localhost
       if(!filter_var($host,FILTER_VALIDATE_IP) && $host !="localhost"){
           throw new InvalidArgumentException("Require an address IP or localhost");
       }
           
       $this->driver = $driver;
       $this->host = $host;
       $this->dbname = $dbname;
       $this->username = $username;
       $this->password = $password;
             
   }
   /**
    * 
    * @throws Exception
    */
   private function connect()
   {
       $dsn= $this->driver . ':dbname='. $this->dbname . ';host='. $this->host;
       try {
           $this->connexion=new PDO($dsn, $this->username, $this->password);
           
     
       }catch (Exception $e){
           throw $e;
       }
   }
   /**
    * 
    * @return boolean
    */
   private function isConnected()
   {
       //instanceof permet de tester si la connexion est de type PDO
       return (bool)($this->connexion instanceof PDO);   
   }
   /**
    * Lazy Loading
    * on récupère la connexion seulement quand nous avons besoin
    * Bonus: permet d'allger le traitement
    */
   public function getConnexion()
   {
       if(!$this->isConnected()){
           $this->connect();
       }
       return $this->connexion;
       
   }
    
}