<?php 

require_once 'app/Request.php';
require_once 'app/CoordsService.php';
require_once 'app/CoordsMapper.php';
require_once 'app/Db.php';


try{
    $request = new Request;
    $db=new Db('mysql','localhost','project','project','0000');
    $coordsMapper=new CoordsMapper($db); //composition
    $coordsService = new CoordsService($coordsMapper);
    
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
        echo $coordsService->upload($request->getParam('data'));
        break;
    case "loadAddresses" :
        echo $coordsService->readAll();
        break;
    case "loadAddress" :
        echo $coordsService->readById($request->getParam('id'));
        break;
    case "delete" :
       echo $coordsMapper->delete($request->getParam('id'));
       break;
    case "save" :     
       echo $coordsService->save(
                $request->getParam('nom'),
                $request->getParam('description'),
                $request->getParam('adresse'),
                $request->getParam('url'),
                $request->getParam('id')
           );
        break;
}


