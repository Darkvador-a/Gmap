<?php 

require_once 'app/Request.php';
require_once 'app/Coords.php';
require_once 'app/Db.php';


try{
    $request = new Request;
    $db=new Db('mysql','192.180.10.120','project','project','0000');
    var_dump($db); exit;
    // Vérifie AJAX 
    if (!$request->isXhr()) {
        echo 'BAD METHOD';
        exit(0);
    }
    $action = $request->getParam('action');
    $coords=new Coords();
} catch(Exception $e) {
    echo $e->getMessage();
}
exit(0);
switch($action) {
    case "upload" :
        $coords-> importCSV();
        break;
    case "loadAddresses" :
        $coords->readCoords();
        break;
    case "loadAddress" :
        $id = (int) $_GET['id'];
        $coords->readById($id);
        break;
    case "delete" :
       $id = (int) $_GET['id'];
       $coords->deleteCoords($id);
       break;
    case "save" :
        $coords->newCoords();
        break;
}

