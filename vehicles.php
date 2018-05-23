<?php
class Vehicles { 

 
 private function getSticker($sp3e4d60) { $spef8d22 = DB1::queryFirstField('SELECT payklever_main.vehicle.codigo FROM payklever_main.vehicle WHERE payklever_main.vehicle.idVehicle=%s LIMIT 1', $sp3e4d60);
 if (is_null($spef8d22)) { return null;
 } else { return $spef8d22;
 } }
 private function checkVehicle($idvehicle) { $spd03e3f = DB1::queryFirstField('SELECT matricula FROM vehicle WHERE vehicle.idVehicle=%s  LIMIT 1', $idvehicle);
 if (is_null($spd03e3f)) { return true;
 } else { return false;
 } } 

 private function checkSticker($idvehicle) { $query = DB1::queryFirstField('SELECT codigo FROM vehicle WHERE vehicle.idVehicle=%s LIMIT 1', $idvehicle);
 if (!is_null($query)) { return $query;
 } else { return false;
 }
}

private function checkCard($sp3e4d60) { 
      $spef8d22 = DB1::queryFirstField('SELECT payklever_main.ecocliente.idEcocliente FROM payklever_main.ecocliente WHERE payklever_main.ecocliente.idClient=%s LIMIT 1', $sp3e4d60);
 if (is_null($spef8d22)) { 
      return null;
 } else { 
      return $spef8d22;
 } 
} 
public function searchVehicle($sp8eba51) { 
            $sp8eba51 = strtoupper($sp8eba51); 
            $sp35360c = DB1::queryFirstRow('SELECT 
            vehicle.idVehicle as vehicle_id,
            vehicle.matricula as plate,ß
            vehicle.state as state,
            vehicle.codigo as sticker_code,
            stickerTable.serial as sticker_serial,
            vehicle.marca as make, 
            vehicle.modelo as model, 
            vehicle.color as color, 
            vehicle.nombre as name,
            (SELECT EXISTS(SELECT 1 FROM sociosParking WHERE sociosParking.matricula=%s)) as subscriber 
            FROM vehicle,stickerTable 
            WHERE stickerTable.stickerCode=vehicle.codigo 
            AND vehicle.matricula=%s ', $sp8eba51, $sp8eba51); return $sp35360c; }

public function checkVehicleCliente($sp8eba51, $sp655ee2) { 
            $sp8eba51 = strtoupper($sp8eba51); 
            $sp35360c = DB1::queryFirstRow('SELECT 
            vehicle.idVehicle as vehicle_id,
            vehicle.matricula as plate,
            vehicle.state as state,
            vehicle.codigo as sticker_code,
            stickerTable.serial as sticker_serial,
            vehicle.marca as make, 
            vehicle.modelo as model, 
            vehicle.color as color, 
            vehicle.nombre as name  
            FROM vehicle,stickerTable 
            WHERE stickerTable.stickerCode=vehicle.codigo 
            AND vehicle.matricula=%s and vehicle.idCliente=%s ', $sp8eba51, $sp655ee2); return $sp35360c; } 


public function getVehicles($idUser) { 
            $vehicles = DB1::query('SELECT
payklever_main.vehicle.idVehicle as vehicle_id,
payklever_main.vehicle.matricula as plate,
payklever_main.vehicle.state as state,
payklever_main.vehicle.marca as brand,
payklever_main.vehicle.modelo as model,
payklever_main.vehicle.anyo as year,
payklever_main.vehicle.color as color,
payklever_main.stickerTable.serial as sticker_code,
payklever_main.ecocliente.thefourfantastics as lastFourDigits,
CASE payklever_main.ecocliente.kindofmagic
WHEN 1 THEN \'VISA\'
WHEN 2 THEN \'MASTERCARD\'
WHEN 3 THEN \'AMERICAN EXPRESS\'
WHEN 4 THEN \'DISCOVER\'
WHEN 5 THEN \'DINNERS CLUB\'
WHEN 6 THEN \'JCB\'
END as \'typeCard\',
payklever_main.vehicle.nombre as name
FROM
payklever_main.vehicle
LEFT JOIN payklever_main.ecocliente
ON payklever_main.vehicle.idecoCliente = payklever_main.ecocliente.idEcocliente
LEFT JOIN payklever_main.stickerTable 
ON payklever_main.vehicle.codigo=payklever_main.stickerTable.stickerCode
WHERE
idCliente=\'' . $idUser . '\'', true);
            return $vehicles;
          }


public function deleteVehicle($idvehicle){
      
      if (!$this->checkVehicle($idvehicle)) { 
            $sticker = $this->checkSticker($idvehicle);
            if ($sticker) {
              $sp4285bd = DB1::update('stickerTable', array('used' => 0), 'stickerCode=%s', $sticker);
            }
            $sp4285bd = DB1::delete('vehicle', 'idVehicle=%s', $idvehicle);
          
            return $sp4285bd;
    } else { 
            return false;
 } 
       

}

public function getVehiclesCard($idCard){
      
     $vehicles = DB1::query('SELECT
payklever_main.vehicle.idVehicle as \'id\',
payklever_main.vehicle.matricula as \'plate\',
payklever_main.vehicle.state as \'state\',
payklever_main.vehicle.idecoCliente as \'idCard\',
payklever_main.stickerTable.serial as \'sticker_code\',
payklever_main.vehicle.marca as \'brand\',
payklever_main.vehicle.modelo as \'model\',
payklever_main.vehicle.anyo as \'year\',
payklever_main.vehicle.color as \'color\',
payklever_main.vehicle.nombre as \'name\'
FROM
payklever_main.vehicle
JOIN payklever_main.stickerTable 
ON payklever_main.vehicle.codigo=payklever_main.stickerTable.stickerCode
WHERE
payklever_main.vehicle.idecoCliente=\'' . $idCard . '\'', true); return $vehicles; 
}


public function updateVehicle($idVehicle, $data, $userId){

        $sp5d571b = trim($idVehicle);

       if (isset($data['sticker_code']) && !isset($data['no_sticker'])) { 
        $spadd2ac = trim($data['sticker_code']);
       if (!empty($spadd2ac)) { 

        $spadd2ac = strtoupper($spadd2ac);
       $sp07876a = DB1::queryFirstField('SELECT payklever_main.stickerTable.stickerCode FROM payklever_main.stickerTable WHERE payklever_main.stickerTable.serial=%s', $spadd2ac);
       if (!is_null($sp07876a)) { 

        $sp023bdd['codigo'] = $sp07876a;
       $sp8d6e29['sticker_code'] = $sp07876a;
       $sp286ebc = DB1::queryFirstField('SELECT payklever_main.vehicle.idVehicle FROM payklever_main.vehicle WHERE payklever_main.vehicle.codigo=%s AND payklever_main.vehicle.idVehicle!=%s', $sp07876a, $sp5d571b);
       if (!is_null($sp286ebc)) { 
        $var = array('code' => 400, 'plate' => $sp8d6e29['plate'], 'sticker' => $spadd2ac);
       
       
       } 
      } else { 
        $var = array('code' => 500, 'plate' => $sp8d6e29['plate'], 'sticker' => $spadd2ac);
       
       
       }
        } 
      } else { 
        $sp023bdd['codigo'] = NULL;
       $sp8d6e29['sticker_code'] = NULL;
       } 

       if (isset($data['brand'])) { 
        $sp2169bd = $data['brand'];
       if (!empty($sp2169bd)) { 
        $sp023bdd['marca'] = $sp2169bd;
       $sp8d6e29['brand'] = $sp2169bd;
       } 
      } if (isset($data['model'])) { 
        $sp66280f = $data['model'];
       if (!empty($sp66280f)) { 
        $sp023bdd['modelo'] = $sp66280f;
       $sp8d6e29['model'] = $sp66280f;
       } 
      } if (isset($data['year'])) { $spa7a75c = 
        $data['year'];
       if (!empty($spa7a75c)) { 
        $sp023bdd['anyo'] = $spa7a75c;
       $sp8d6e29['year'] = $spa7a75c;
       } 
      } if (isset($data['color'])) { 
        $spa1c179 = $data['color'];
       if (!empty($spa1c179)) { 
        $sp023bdd['color'] = $spa1c179;
       $sp8d6e29['color'] = $spa1c179;
       } 
      } if (isset($data['name'])) { 
        $sp50be76 = $data['name'];
       if (!empty($sp50be76)) { 
        $sp023bdd['nombre'] = $sp50be76;
       $sp8d6e29['name'] = $sp50be76;
       } 
      } DB1::$error_handler = false;
       DB1::$throw_exception_on_error = true;
       try { 

         if (is_null($sp023bdd['codigo']) && isset($data['no_sticker'])){
        $stickerQuery = $this->getSticker($sp5d571b);
        $sp2f59c3 = DB1::update('stickerTable', array('used' => 0), 'stickerCode=%s', $stickerQuery);
        }

        DB1::update('vehicle', $sp023bdd, 'idVehicle=%s', $sp5d571b);
        $ifCard = $this->checkCard($userId);

        if (!is_null($data['sticker_code']) && !is_null($ifCard) && !isset($data['no_sticker']) ) { 
        $sp2f59c3 = DB1::update('stickerTable', array('used' => 1), 'serial=%s', $spadd2ac);
       }


      // $sp8d6e29['lel'] = $sp5d571b;
       DB1::debugMode(false);
       $var = array('code' => 200, 'vehicle' => $sp8d6e29);
       
      
       } catch (MeekroDBException $spa2b843) { DB1::debugMode(false);
       $var = array('code' => 100, 'error' => $spa2b843->getMessage());
       } DB1::$error_handler = 'meekrodb_error_handler';
       DB1::$throw_exception_on_error = false;

       return $var;
                      
      }


public function addVehicle($data, $idClient){

             //$idClient = $data['idClient'];
             $arrayVehicle['idCliente'] = $idClient;
             $arrayDebug['client_id'] = $arrayVehicle['idCliente'];
             $plate = $data['plate'];
             $arrayVehicle['matricula'] = $plate;
             $arrayDebug['plate'] = $arrayVehicle['matricula'];
             $state = $data['state'];
             $arrayVehicle['state'] = $state;
             $arrayDebug['state'] = $arrayVehicle['state'];



             if (isset($idClient) && !empty($idClient) && isset($data['plate']) && !empty($data['plate']) && isset($data['state']) && !empty($data['state'])) {

                  $queryIdVehicle = DB1::queryFirstField('SELECT payklever_main.vehicle.idVehicle FROM payklever_main.vehicle WHERE ( payklever_main.vehicle.matricula=%s AND payklever_main.vehicle.state=%s) LIMIT 1', $plate, $state);
                  if (is_null($queryIdVehicle)) {
                              if (isset($data['sticker_code']) && !empty($data['sticker_code']) && !isset($data['no_sticker'])) {
                              $stickerAux = $data['sticker_code'];
                              $sticker = strtoupper($stickerAux);
                              $querySticker = DB1::queryFirstField('SELECT payklever_main.stickerTable.stickerCode FROM payklever_main.stickerTable WHERE payklever_main.stickerTable.serial=%s LIMIT 1', $sticker);
                              $arrayDebug['sticker_code'] = $sticker;
                              if (!is_null($querySticker)) {
                                    $arrayVehicle['codigo'] = $querySticker;
                                    $queryIdVehicle = DB1::queryFirstField('SELECT payklever_main.vehicle.idVehicle FROM payklever_main.vehicle WHERE payklever_main.vehicle.codigo=%s LIMIT 1', $querySticker);
                                    
                                    if (!is_null($queryIdVehicle)){
                                          $var = array('code' => 400, 'plate' => $plate, 'sticker' => $sticker);
                                          return $var;
                        
                                    }
                              } else {
                                    $var = array('code' => 500, 'plate' => $plate, 'sticker'=>$sticker);
                                    return $var;
                              }
                        }

                        if (isset($data['card_id']) && !empty($data['card_id'])) {
                              $arrayVehicle['idecoCliente'] = $data['idCard'];
                              $arrayDebug['card_id'] = $arrayVehicle['idecoCliente'];


                        } else {
                              $checkCard = $this->checkCard($idClient);
                              if (!is_null($checkCard)) {
                                    $arrayVehicle['idecoCliente'] = $checkCard;
                                    $arrayDebug['card_id'] = $arrayVehicle['idecoCliente'];
                              }
                        }

                        if (isset($data['model']) && !empty($data['model'])) { 
                          $arrayVehicle['modelo'] = $data['model'];
                         $arrayDebug['model'] = $arrayVehicle['modelo'];
                         } if (isset($data['brand']) && !empty($data['brand'])) { 
                          $arrayVehicle['marca'] = $data['brand'];
                         $arrayDebug['brand'] = $arrayVehicle['marca'];
                         } if (isset($data['year']) && !empty($data['year'])) { 
                          $arrayVehicle['anyo'] = $data['year'];
                         $arrayDebug['year'] = $arrayVehicle['anyo'];
                         } if (isset($data['color']) && !empty($data['color'])) { 
                          $arrayVehicle['color'] = $data['color'];
                         $arrayDebug['color'] = $arrayVehicle['color'];
                         } if (isset($data['name']) && !empty($data['name'])) { 
                          $arrayVehicle['nombre'] = $data['name'];
                         $arrayDebug['name'] = $arrayVehicle['nombre'];
                         } 


                        $arrayVehicle['idVehicle'] = 'vid_' . md5(microtime(true) . $plate . uniqid(rand(), true));
                        $arrayDebug['vehicle_id'] = $arrayVehicle['idVehicle'];
                        DB1::$error_handler = false;
                        DB1::$throw_exception_on_error = false;

                       
                        DB1::insert('vehicle', $arrayVehicle);
                        $dbId = DB1::insertId();

                       if (isset($querySticker) && !empty($querySticker)) {
                                $aux = DB1::update('stickerTable', array('used' => 1), 'serial=%s', $sticker);
                        }

                        $var = array('code' => 200, 'vehicle' => $arrayDebug);

                        DB1::$error_handler = 'meekrodb_error_handler';
                       DB1::$throw_exception_on_error = false;
                
                        } else { 
                              $var = array('code' => 300, 'vehicle_id' => $queryIdVehicle, 'plate' => $plate);
                              return $var;
                        //$spd4d3ee = json_encode(array('return' => $sp84b9a6), JSON_FORCE_OBJECT);
                        } 
                  } else { $var = array('code' => -100, 'error' => 'bad request.Missing required fields');
                  //$spd4d3ee = json_encode(array('return' => $sp84b9a6), JSON_FORCE_OBJECT);
                  }
                  return $var;

            }

            



}

            



