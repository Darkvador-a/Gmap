<?php 

require_once 'app/Request.php';
require_once 'app/Coords.php';
require_once 'app/Db.php';


try{
    $request = new Request;
    $db=new Db('mysql','localhost','project','project','0000');
    $coords=new Coords($db); //composition
    
    // Vérifie AJAX 
    if (!$request->isXhr()) {
        echo 'BAD METHOD';
        exit(0);
    }
    $action = $request->getParam('action');
    
} catch(Exception $e) {
    echo $e->getMessage();
}

switch($action) {
    case "upload" :
        echo $coords-> upload($request->getParam('data'));
        break;
    case "loadAddresses" :
        echo $coords->fetchAll();
        break;
    case "loadAddress" :
        echo $coords->findById($request->getParam('id'));
        break;
    case "delete" :
       echo $coords->delete($request->getParam('id'));
       break;
    case "save" :     
       echo $coords->save(array(
                'id' => (int) $request->getParam('id'),
                'nom' => (string) $request->getParam('nom'),
                'desc' => (string) $request->getParam('description'),
                'adresse' => (string) $request->getParam('adresse'),
                'url' => (string) $request->getParam('url'),
            ));
        break;
}


