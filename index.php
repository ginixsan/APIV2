<?php
date_default_timezone_set('America/New_York');
require_once('vendor/autoload.php');
use Firebase\JWT\JWT;
//carrega el config on vulguis
require 'config_file.php';
require 'vehicles.php';
require 'payments.php';
require 'user.php';
require_once './lib/Stripe.php';
require_once './config/config.php';
require './stripe/init.php';
//require '../../PHPMailerAutoload.php';
//per poder utilitzar les claus dins del framwework hem de mapejarles
//Flight::set('key', $key);
Flight::set('public', $public);
Flight::set('private', $private);

Flight::set('flight.log_errors', true);

function verifyJWT()
{
    //$key=Flight::get('key');
    $public=Flight::get('public');
    $private= Flight::get('private');

    try {
        $token=getBearerToken();
        $data = JWT::decode($token, $public, array('RS256'));
        return $data->data->userId;
    }
    catch (\Firebase\JWT\ExpiredException $e) {
        print "Error!: " . $e->getMessage() . " ".$e->getCode();
        return false;//
        //die();
    }
}
function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function getBearerToken() {
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

Flight::route("/", function(){
    $code = 404;
    Flight::before('stop', function(&$params) use ($code) {
        $params[0] = $code;
        Flight::map('error', function(Exception $ex){
    // Handle error
    echo $ex->getTraceAsString();
    });


    });
    Flight::render('header', array('heading' => 'IT SEEMS YOU ARE NOT THAT KLEVER AFTER ALL...'), 'header_content');
    Flight::render('body', array('body' => 'World'), 'body_content');
    Flight::render('not_found', array('title' => 'Home Page'));


});

//Get vehicles
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /vehicle", function () {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new Vehicles();
        $results = $object->getVehicles($userId);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});


//Get vehicles with idCard
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /vehicleCard/@idCard", function ($idCard) {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new Vehicles();
        $results = $object->getVehiclesCard($idCard);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});


//Edit vehicle
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("PUT /vehicle/@idVehicle", function ($idVehicle) {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        //$data = Flight::request()->data;
        $body = Flight::request()->getBody();
        $data= json_decode($body,true);
        $object = new Vehicles();
        $results = $object->updateVehicle($idVehicle, $data, $userId);
        //$retorn=array('code'=>201,'results'=>$data);
        Flight::json($results);
    }

});

//Add vehicle
////////////////////////////////////////////////////////////////////////////////////////////////
 Flight::route('POST /vehicle/', function () {
 $userId = verifyJWT();
 if (!$userId) {
    Flight::json('TOKEN EXPIRED');
 } else {
    //Flight::json('TOKEN EXPIRED');
  $sp8e0ee8 = Flight::request()->data;
  $object = new Vehicles();
  $spfa439a = $object->addVehicle($sp8e0ee8, $userId);
 Flight::json($spfa439a);
 }
});


//Delete vehicle
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("DELETE /vehicle/@idVehicle", function ($idVehicle) {
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    } else {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $body = Flight::request()->data;
        //$data= json_decode($body,true);
        $object = new Vehicles();
        $results = $object->deleteVehicle($idVehicle);

    if($results){
        $retorn=array('code'=>200,'results'=>$idVehicle);
        Flight::halt(201, json_encode($retorn));
    }else{
        //Flight::json($retorn);
        $retorn=array('code'=>406,'results'=> 'VEHICLE DOES NOT EXIST');
        Flight::halt(201, json_encode($retorn));
    }
    }

});

//Get payments
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /payment", function () {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new Payment();
        $results = $object->getWallets($userId);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});

//Edit payment of vehicle
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("PUT /updateVehicleCard/@idVehicle", function ($idVehicle) {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        //$data = Flight::request()->data;
        $body = Flight::request()->getBody();
        $data= json_decode($body,true);
        $object = new Payment();
        $results = $object->updateVehicleCard($data, $idVehicle);
        //$retorn=array('code'=>201,'results'=>$data);
        Flight::json($results);
    }

});

//Add payment
////////////////////////////////////////////////////////////////////////////////////////////////
 Flight::route('POST /payment', function () {
 $userId = verifyJWT();
 if (!$userId) {
    Flight::json('TOKEN EXPIRED');
 } else {
    //Flight::json('TOKEN EXPIRED');
  $sp8e0ee8 = Flight::request()->data;
  $object = new Payment();
  $spfa439a = $object->addPayment($sp8e0ee8, $userId);
 Flight::json($spfa439a);
 }
});

//Delete payment
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("DELETE /payment/@idEco", function ($idEco) {
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    } else {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $body = Flight::request()->data;
        //$data= json_decode($body,true);
        $object = new Payment();
        $results = $object->deletePayment($idEco);
         Flight::json($results);
    }

});

//Add user
////////////////////////////////////////////////////////////////////////////////////////////////
 Flight::route('POST /registerMail', function () {
 //$userId = verifyJWT();
 
    Flight::json('TOKEN EXPIRED');
  $sp8e0ee8 = Flight::request()->data;
  $object = new User();
  $spfa439a = $object->registerMail($sp8e0ee8);
 Flight::halt(200, json_encode($spfa439a));
 
});

 //Add dades to user
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("PUT /addDades/@idClient", function ($idClient) {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    //$userId=verifyJWT();
    //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
    //$data = Flight::request()->data;
    $body = Flight::request()->getBody();
    $data= json_decode($body,true);
    $object = new User();
    $results = $object->addDades($data, $idClient);
    //$retorn=array('code'=>201,'results'=>$data);
     Flight::json($results);
});

//Update user
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("PUT /updateUser/@idClient", function ($idClient) {
     $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
    $body = Flight::request()->getBody();
    $data= json_decode($body,true);
    $object = new User();
    $results = $object->updateUser($data, $idClient);
    //$retorn=array('code'=>201,'results'=>$data);
     Flight::json($results);
 }
});

//Get user history
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /userHistory", function () {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new User();
        $results = $object->getHistory($userId);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});

//Get user history
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /paginatedUserHistory", function () {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new User();
        $results = $object->getPaginatedHistory($userId);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});


//Get personal information
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /personal", function () {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new User();
        $results = $object->getPersonal($userId);
        $retorn=array('code'=>200,'results'=>$results);
        Flight::json($retorn);
    }

});

//Get user history
////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route("GET /historyPage/@page", function ($page) {
    //aqui el que fem es trucar a la funcio per comprobar el JWT i rebre el parking si es valid
    $userId=verifyJWT();
    if(!$userId)
    {
        Flight::json('TOKEN EXPIRED');

    }
  
    else
    {
        //AQUI CRIDEM A LA CLASSE O METODE QUE NECESITEM I FEM LES COSES QUE SIGUI 
        $object = new User();
        $results = $object->getHistoryPage($userId, $page);
        //$retorn=array('code'=>200,'results'=>$results);
        Flight::json($results);
    }
 
});


Flight::map('notFound', function(){

    Flight::render('header', array('heading' => 'IT SEEMS YOU ARE NOT THAT KLEVER AFTER ALL...'), 'header_content');
    Flight::render('body', array('body' => 'World'), 'body_content');
    Flight::render('not_found', array('title' => 'Home Page'));
});
Flight::map('error', function(Exception $ex){
    // Handle error
    if($ex->getMessage()=='Signature verification failed')
    {
        Flight::json('SIGNATURE VERIFICATION FAILED');
    }
    elseif ($ex->getMessage()=='Wrong number of segments')
    {
        Flight::json('BAD TOKEN ENCODING OR NO TOKEN');
    }
    else
    {
        echo $ex->getMessage().' '.$ex->getLine().' '.$ex->getCode();

    }
});


Flight::start();
?>