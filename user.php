<?php


class User {
	

 function crypto_rand_secure($spb04eaa, $sp64b5ff) { $sp16dbf9 = $sp64b5ff - $spb04eaa;
 if ($sp16dbf9 < 0) { return $spb04eaa;
 } $sp4710ff = log($sp16dbf9, 2);
 $sp7af28c = (int) ($sp4710ff / 8) + 1;
 $spb109d1 = (int) $sp4710ff + 1;
 $sp951a66 = (int) (1 << $spb109d1) - 1;
 do { $sp37748d = hexdec(bin2hex(openssl_random_pseudo_bytes($sp7af28c)));
 $sp37748d = $sp37748d & $sp951a66;
 } while ($sp37748d >= $sp16dbf9);
 return $spb04eaa + $sp37748d;
 } function getToken($spb66475 = 32) { $sp62b468 = '';
 $spe1a1b4 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 $spe1a1b4 .= 'abcdefghijklmnopqrstuvwxyz';
 $spe1a1b4 .= '0123456789';
 for ($spc1cb57 = 0;
 $spc1cb57 < $spb66475;
 $spc1cb57++) { $sp62b468 .= $spe1a1b4[$this->crypto_rand_secure(0, strlen($spe1a1b4))];
 } return $sp62b468;
 } 
 function buscaMail($spb064c7) { $spd1ecfe = DB1::queryFirstField('SELECT email FROM cliente WHERE idCliente=%s', $spb064c7);
 return $spd1ecfe;} 
 function buscaNombre($spb064c7) { $spd1ecfa = DB1::queryFirstField('SELECT nombre FROM cliente WHERE idCliente=%s', $spb064c7);
return $spd1ecfa; } 
function enviaMailConf($spf6e577, $sp62b468, $name) { 
	//require 'PHPMailerAutoload.php';
	$spcd27d9 = new PHPMailer();
 $spcd27d9->setFrom('signup.confirmation@payklever.com', 'Payklever');
 $spcd27d9->addReplyTo('signup.confirmation@payklever.com', 'Payklever');
 $spcd27d9->addAddress($spf6e577);
 $spcd27d9->addBCC('signup.confirmation@payklever.com');
 $spcd27d9->isHTML(true);
 $spcd27d9->Subject = 'Mail Confirmation';
 $spcd27d9->Body = strtr(file_get_contents('mails/email_verification.html'), array('%varName%' => $name , '%varVerification%' => $sp62b468));
 if (!$spcd27d9->send()) { return $spcd27d9->ErrorInfo;
 } else { return true;
 } } 

public function getHistory($userId) {

 $data = DB1::query("SELECT
parkings.nombre AS parkingName,
transa.amount AS amountDollars,
transa.fechaHora AS dateTime,
DATE_FORMAT(transa.fechaHora, '%Y-%m-%d') AS date,
DATE_FORMAT(transa.fechaHora,'%H:%i:%s') AS time,
transa.parkingId AS parking_id,
transa.tiempoMin AS duration,
vehicle.idVehicle AS vehicle_id,
vehicle.matricula AS plate,
vehicle.nombre AS name,
CONCAT(FLOOR(transa.tiempoMin/60),'h',MOD(transa.tiempoMin,60),'m') AS timeHoursMin,
parkings.direccion AS parkingAdress,
parkings.ciudad as parkingCity
FROM
parkings
RIGHT JOIN transa
ON parkings.idParking = transa.parkingId 
LEFT JOIN vehicle
ON transa.vehiculoId = vehicle.idVehicle
WHERE
idCliente='".$userId."' ORDER BY
transa.fechaHora DESC", true); 
 return $data; 
} 

public function getPaginatedHistory($userId) {

 $data = DB1::query('SELECT
parkings.nombre AS parkingName,
transa.amount AS amountDollars,
transa.fechaHora AS dateTime,
transa.parkingId AS parking_id,
transa.tiempoMin AS duration,
vehicle.idVehicle AS vehicle_id,
vehicle.matricula AS plate,
vehicle.nombre AS name,
CONCAT(FLOOR(transa.tiempoMin/60),\'h \',MOD(transa.tiempoMin,60),\'m\') AS timeHoursMin,
parkings.direccion AS parkingAdress,
parkings.ciudad as parkingCity
FROM
parkings
RIGHT JOIN transa
ON parkings.idParking = transa.parkingId 
LEFT JOIN vehicle
ON transa.vehiculoId = vehicle.idVehicle
WHERE
idCliente=%s ORDER BY
transa.fechaHora DESC', $userId); 
 return $data; 
} 


public function getPersonal($userId) {

	$data = DB1::query('SELECT
payklever_main.cliente.idCliente as client_id,
payklever_main.cliente.nombre as name,
payklever_main.cliente.apellido as lastname,
payklever_main.cliente.direccion1 as address1,
payklever_main.cliente.direccion2 as address2,
payklever_main.cliente.state as state,
payklever_main.cliente.cp as zip,
payklever_main.cliente.ciudad as city,
payklever_main.cliente.email as email,
payklever_main.cliente.telefono as phone,
payklever_main.cliente.fechaNac as dob,
payklever_main.cliente.country as country,
payklever_main.cliente.codePhone as codephone,
CASE payklever_main.cliente.sexo
WHEN 1 THEN \'male\'
WHEN 2 THEN \'female\'
END as gender
FROM
payklever_main.cliente
WHERE
idCliente=\'' . $userId . '\'', true);  return $data;
}


public function registerMail($data){
	$sp21f56f = trim($data['email']);
 $sp2ad197 = $data['pwd'];
 $spa56580 = password_hash($sp2ad197, PASSWORD_DEFAULT);
 $sp00c309['pass'] = $spa56580;
 $sp00c309['email'] = $sp21f56f;
 $sp00c309['idCliente'] = 'uid_' . md5(microtime(true) . $sp21f56f . uniqid(rand(), true));
 DB1::$error_handler = false;
 DB1::$throw_exception_on_error = true;
 try { 
 	DB1::insert('cliente', $sp00c309);
 $var = array('code' => 200, 'idClient' => $sp00c309['idCliente']);
 //$spffb61f = json_encode(array('return' => $spaad98f), JSON_FORCE_OBJECT);
 //echo stripslashes($spffb61f);
 } catch (MeekroDBException $sp57fc12) { 
 	$var = array('code' => 100, 'error' => $sp57fc12->getMessage());
 //$spffb61f = json_encode(array('return' => $spaad98f), JSON_FORCE_OBJECT);
 //echo stripslashes($spffb61f);
 } DB1::$error_handler = 'meekrodb_error_handler';
 DB1::$throw_exception_on_error = false;
 return $var;

}

function getPaginatedHistoryWithPage($spb064c7, $sp68f24c) { 
	$sp3a0f42 = DB1::query('SELECT
parkings.nombre AS parkingName,
transa.amount AS amountDollars,
transa.fechaHora AS dateTime,
transa.parkingId AS parking_id,
transa.tiempoMin AS duration,
vehicle.idVehicle AS vehicle_id,
vehicle.matricula AS plate,
vehicle.nombre AS name,
CONCAT(FLOOR(transa.tiempoMin/60),\'h \',MOD(transa.tiempoMin,60),\'m\') AS timeHoursMin,
parkings.direccion AS parkingAdress,
parkings.ciudad as parkingCity
FROM
parkings
RIGHT JOIN transa
ON parkings.idParking = transa.parkingId 
LEFT JOIN vehicle
ON transa.vehiculoId = vehicle.idVehicle
WHERE
idCliente=%s ORDER BY
transa.fechaHora DESC', $spb064c7);
 $sp0615e8 = DB1::count();
 $sp082276 = ceil($sp0615e8 / 15);
 $spcc73f1 = $sp68f24c * 15 - 15;
 $sp5369cc = DB1::query("SELECT\nparkings.nombre AS parkingName,\ntransa.amount AS amountDollars,\ntransa.fechaHora AS dateTime,\ntransa.parkingId AS parking_id,\ntransa.tiempoMin AS duration,\nvehicle.idVehicle AS vehicle_id,\nvehicle.matricula AS plate,\nvehicle.nombre AS name,\nCONCAT(FLOOR(`transa`.`tiempoMin`/60),'h ',MOD(`transa`.`tiempoMin`,60),'m') AS timeHoursMin,\nparkings.direccion AS parkingAdress,\nparkings.ciudad as parkingCity\nFROM\n`parkings`\nRIGHT JOIN `transa`\nON `parkings`.`idParking` = `transa`.`parkingId` \nLEFT JOIN `vehicle`\nON `transa`.`vehiculoId` = `vehicle`.`idVehicle`\nWHERE\nidCliente=%s ORDER BY\n`transa`.`fechaHora` DESC LIMIT {$spcc73f1},15", $spb064c7);
 $sp8f96f9['totalPages'] = $sp082276;
 $sp8f96f9['result'] = $sp5369cc;
 return $sp8f96f9;
 } 

public function addDades($data, $idCliente){

 $spc5d489['nombre'] = trim($data['first']);
 $spc5d489['apellido'] = trim($data['last']);
 $spc5d489['telefono'] = trim($data['phone']);
 if (isset($data['address1']) && !empty($data['address1'])) { $spc5d489['direccion1'] = trim($data['address1']);
 } if (isset($data['address2']) && !empty($data['address2'])) { $spc5d489['direccion2'] = trim($data['address2']);
 } if (isset($data['gender']) && !empty($data['gender'])) { $spc5d489['sexo'] = $data['gender'];
 } if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) { $spc5d489['fechaNac'] = $data['date_of_birth'];
 } if (isset($data['zip']) && !empty($data['zip'])) { $spc5d489['cp'] = $data['zip'];
 } if (isset($data['codephone']) && !empty($data['codephone'])) { $spc5d489['codePhone'] = trim($data['codephone']);
 } if (isset($data['city']) && !empty($data['city'])) { $spc5d489['ciudad'] = trim($data['city']);
 } if (isset($data['state']) && !empty($data['state'])) { $spc5d489['state'] = trim($data['state']);
 } if (isset($data['country']) && !empty($data['country'])) { $spc5d489['country'] = trim($data['country']);
 } $spb064c7 = $idCliente;
 DB1::$error_handler = false;
 DB1::$throw_exception_on_error = true;
 try { DB1::update('cliente', $spc5d489, 'idCliente=%s', $spb064c7);
 $sp62b468 = $this->getToken();
 $spf6e577 = $this->buscaMail($spb064c7);
 $name = $this->buscaNombre($spb064c7);
 $spd74758 = DB1::insert('confCliente', array('idCliente' => $spb064c7, 'email' => $spf6e577, 'token' => $sp62b468, 'fechaCaduca' => DB1::sqleval('DATE_ADD( NOW(), INTERVAL 24 HOUR )')));
 $sp430f48 = $this->enviaMailConf($spf6e577, 'http://www.payklever.com/emailConf.php?token=' . $sp62b468, $name);
 $var = array('code' => 200, 'idClient' => $spb064c7);
 //$sp8f96f9 = json_encode(array('return' => $sp00ee7c), JSON_FORCE_OBJECT);
 //echo stripslashes($sp8f96f9);
 } 
 catch (MeekroDBException $spbe95b2) { 
 	$var = array('code' => 100, 'error' => $spbe95b2->getMessage());
 //$sp8f96f9 = json_encode(array('return' => $sp00ee7c), JSON_FORCE_OBJECT);
 //echo stripslashes($sp8f96f9);
 } 
 DB1::$error_handler = 'meekrodb_error_handler';
 DB1::$throw_exception_on_error = false;
 return $var;
}

public function getHistoryPage($userId, $page){
	
 if ($page !=0) { 
 	$sp0a3b08 = $this->getPaginatedHistoryWithPage($userId, $page);
 } else { 
 	$sp0a3b08 = $this->getHistory($userId);
 } 
 $var = array('code' => 200, 'data' => $sp0a3b08);
 return $var;
}

public function updateUser($data , $idClient){

$sp498786 = trim($idClient);
 $sp12286f['user_id'] = $sp498786;
 if (isset($data['name'])) { 
 	$spbc8d4c = $data['name'];
 if (!empty($spbc8d4c)) { 
 	$sp1c4b87['nombre'] = $spbc8d4c;
 $sp12286f['name'] = $spbc8d4c;
 } 
} 
if (isset($data['last_name'])) { 
	$sp251617 = $data['last_name'];
 if (!empty($sp251617)) { 
 	$sp1c4b87['apellido'] = $sp251617;
 $sp12286f['last_name'] = $sp251617;
 } 
} 
if (isset($data['email'])) { 
	$spf6e577 = $data['email'];
 if (!empty($spf6e577)) { 
 	$sp1c4b87['email'] = $spf6e577;
 $sp12286f['email'] = $spf6e577;
 } 
} 
if (isset($data['address1'])) { 
	$sp01eacf = $data['address1'];
 if (!empty($sp01eacf)) { 
 	$sp1c4b87['direccion1'] = $sp01eacf;
 $sp12286f['address1'] = $sp01eacf;
 } 
} 
if (isset($data['city'])) { 
	$sp9a9f4b = $data['city'];
 if (!empty($sp9a9f4b)) { 
 	$sp1c4b87['ciudad'] = $sp9a9f4b;
 $sp12286f['city'] = $sp9a9f4b;
 } 
} 
if (isset($data['zip_code'])) { 
	$sp3cf4aa = $data['zip_code'];
 if (!empty($sp3cf4aa)) { 
 	$sp1c4b87['cp'] = $sp3cf4aa;
 $sp12286f['zip_code'] = $sp3cf4aa;
 } 
} 
if (isset($data['state'])) { 
	$sp0ea3b5 = $data['state'];
 if (!empty($sp0ea3b5)) { 
 	$sp1c4b87['state'] = $sp0ea3b5;
 $sp12286f['state'] = $sp0ea3b5;
 } 
} 
if (isset($data['country'])) { 
	$spabd596 = $data['country'];
 if (!empty($spabd596)) { 
 	$sp1c4b87['country'] = $spabd596;
 $sp12286f['country'] = $spabd596;
 } 
} 
if (isset($data['date_of_birth'])) { 
	$sp38eeb4 = $data['date_of_birth'];
 if (!empty($sp38eeb4)) { 
 	$sp1c4b87['fechaNac'] = $sp38eeb4;
 $sp12286f['date_of_birth'] = $sp38eeb4;
 } 
} 
if (isset($data['codephone'])) { 
	$spd9a177 = $data['codephone'];
 if (!empty($spd9a177)) {
  $sp1c4b87['codePhone'] = $spd9a177;
 $sp12286f['codephone'] = $spd9a177;
 } 
} 
if (isset($data['phone'])) { 
	$spb4e246 = $data['phone'];
 if (!empty($spb4e246)) { 
 	$sp1c4b87['telefono'] = $spb4e246;
 $sp12286f['phone'] = $spb4e246;
 } 
} 
if (isset($data['gender'])) { 
	$spea36dd = $data['gender'];
 if (!empty($spea36dd)) { 
 	$sp1c4b87['sexo'] = $spea36dd;
 $sp12286f['gender'] = $spea36dd;
 } 
} 
DB1::$error_handler = false;
 DB1::$throw_exception_on_error = true;
 try { 
 	DB1::update('cliente', $sp1c4b87, 'idCliente=%s', $sp498786);
 DB1::debugMode(false);
 $var = array('code' => 200, 'client' => $sp12286f);
 //$sp8f96f9 = json_encode(array('return' => $sp00ee7c), JSON_FORCE_OBJECT);
//die(stripslashes($sp8f96f9));
 } catch (MeekroDBException $spbe95b2) { DB1::debugMode(false);
 $var = array('code' => 100, 'error' => $spbe95b2->getMessage());
 //$sp8f96f9 = json_encode(array('return' => $sp00ee7c), JSON_FORCE_OBJECT);
 //die(stripslashes($sp8f96f9));
 } 
 DB1::$error_handler = 'meekrodb_error_handler';
 DB1::$throw_exception_on_error = false;

 return $var;
}


}