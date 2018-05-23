<?php


class Payment{
	

 function recuperaBud($spa6ade4) { $sp10fbef = DB1::queryFirstField('SELECT
			payklever_main.ecocliente.budespenser as budespenser
			FROM
			payklever_main.ecocliente
			WHERE
			payklever_main.ecocliente.idClient=%s LIMIT 1', $spa6ade4);
 return $sp10fbef;
 } 
 function recuperaMail($sp9b4a02) { $sp0ff730 = DB1::queryFirstField('SELECT
			payklever_main.cliente.email
			FROM
			payklever_main.cliente
			WHERE
			payklever_main.cliente.idCliente=%s LIMIT 1', $sp9b4a02);
 return $sp0ff730;
 } 
 function buscaListaNegra($sp9b4a02) { } 

 function asignaTarjetas($sp9b4a02, $spd5bf6b) { //DB1::debugMode();
 DB1::$error_handler = false;
 DB1::$throw_exception_on_error = true;
 try { 
			DB1::update('vehicle', array('idecoCliente' => $spd5bf6b), "idecoCliente IS NULL AND idCliente=%s", $sp9b4a02);

			$results=DB1::query('SELECT
payklever_main.stickerTable.idStickerTable,
payklever_main.stickerTable.used
FROM
payklever_main.stickerTable,
payklever_main.vehicle
WHERE
payklever_main.stickerTable.stickerCode=payklever_main.vehicle.codigo AND payklever_main.vehicle.idCliente=%s AND payklever_main.vehicle.idecoCliente=%i',$sp9b4a02,$spd5bf6b);

		if(!is_null($results))
		{
				foreach ($results as $row) {
						if($row['used']==0)
						{
							DB1::update('stickerTable', array('used'=>1), "idStickerTable=%s", $row['idStickerTable']);

						}
				}
				
		}
			} catch (MeekroDBException $sp3c1f96) { syslog(LOG_INFO, 'Fallo: ' . $sp3c1f96->getMessage() . '---' . $sp3c1f96->getQuery());
 } DB1::$error_handler = 'meekrodb_error_handler';
 DB1::$throw_exception_on_error = false;
 DB1::debugMode(false);
 } 


public function getWallets($userId) {

			$data = DB1::query('SELECT
	payklever_main.ecocliente.idEcocliente as id,
	payklever_main.ecocliente.thefourfantastics as lastfourdigits,
	CASE payklever_main.ecocliente.kindofmagic
	WHEN 1 THEN \'VISA\'
	WHEN 2 THEN \'MASTERCARD\'
	WHEN 3 THEN \'AMERICAN EXPRESS\'
	WHEN 4 THEN \'DISCOVER\'
	WHEN 5 THEN \'DINNERS CLUB\'
	WHEN 6 THEN \'JCB\'
	END as \'typeCard\'
	FROM
	payklever_main.ecocliente
	WHERE
	idClient=\'' . $userId . '\'', true);
	            return $data;
          }

public function updateVehicleCard($data, $idVehicle){

 $sp5d571b = trim($idVehicle);
 $sp55224d = trim($data['card_id']);
 DB1::$error_handler = false;
 DB1::$throw_exception_on_error = true;
 try { 
 DB1::update('vehicle', array('idecoCliente' => $sp55224d), 'idVehicle=%s', $sp5d571b);
 DB1::debugMode(false);
 $var = array('code' => 200, 'vehicle_id' => $sp5d571b, 'card_id' => $sp55224d);
 //$sp0fe163 = json_encode(array('return' => $sp6a614a), JSON_FORCE_OBJECT);
 //echo stripslashes($sp0fe163);
 } catch (MeekroDBException $spa2b843) { DB1::debugMode(false);
 $var = array('code' => -100, 'error' => $spa2b843->getMessage());
 //$sp0fe163 = json_encode(array('return' => $sp6a614a), JSON_FORCE_OBJECT);
 //echo stripslashes($sp0fe163);
 } DB1::$error_handler = 'meekrodb_error_handler';
 DB1::$throw_exception_on_error = false;
 return $var;
}

public function deletePayment($idEco) {

	$sp0d5f37 = $idEco;
 	$sp976144 = DB1::queryFirstRow('SELECT budespenser,terenshill FROM ecocliente WHERE idEcoCliente=%i', $sp0d5f37);
 	print_r($sp976144);
 	$speab603 = 'sk_live_raW1EN0OGM0EP4tEuV9eaMRO';
 	Stripe::setApiKey($speab603);
	 $sp165188 = Stripe_Customer::retrieve($sp976144['budespenser']);
	 DB1::$error_handler = false;
	 DB1::$throw_exception_on_error = true;
	 try { 
	 	$sp165188->sources->retrieve($sp976144['terenshill'])->delete();
	 } catch (\Stripe\Error\Card $sp83c36e) { 
	 	$spfee792 = $sp83c36e->getJsonBody();
	 $sp58d8d1 = $spfee792['error'];
	 print 'Status is:' . $sp83c36e->getHttpStatus() . '
	';
	 print 'Type is:' . $sp58d8d1['type'] . '
	';
	 print 'Code is:' . $sp58d8d1['code'] . '
	';
	 print 'Param is:' . $sp58d8d1['param'] . '
	';
	 print 'Message is:' . $sp58d8d1['message'] . '
	';
	 } catch (\Stripe\Error\RateLimit $sp83c36e) { 
	 	$spfee792 = $sp83c36e->getJsonBody();
	 $sp58d8d1 = $spfee792['error'];
	 $var = array('code' => 100, 'error' => 'outdbpayment' . $sp58d8d1['message']);
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 return $var;

	 } catch (\Stripe\Error\InvalidRequest $sp83c36e) { 
	 	$spfee792 = $sp83c36e->getJsonBody();
	 $sp58d8d1 = $spfee792['error'];
	 $var = array('code' => 100, 'error' => 'outdbpayment' . $sp58d8d1['message']);
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 return $var;
	 //die;
	 } catch (\Stripe\Error\Authentication $sp83c36e) { } catch (\Stripe\Error\ApiConnection $sp83c36e) { } catch (\Stripe\Error\Base $sp83c36e) { $spfee792 = $sp83c36e->getJsonBody();
	 $sp58d8d1 = $spfee792['error'];
	 $var = array('code' => 100, 'error' => 'outdbpayment' . $sp58d8d1['message']);
	 return $var;
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 //die;
	 } catch (Exception $sp83c36e) { $spfee792 = $sp83c36e->getJsonBody();
	 $sp58d8d1 = $spfee792['error'];
	 $var = array('code' => 100, 'error' => 'outdbpayment' . $sp58d8d1['message']);
	 return $var;
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 //die;
	 } 
	 try { 
	 DB1::delete('ecocliente', 'idEcocliente=%i', $sp0d5f37);
	 $var = array('code' => 200, 'id' => 'ok');
	 return $var;
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 } catch (MeekroDBException $sp83c36e) { 
	 	$var = array('code' => 100, 'error' => 'outdbpayment' . $sp83c36e->getMessage());
	 	return $var;
	 //$sp773017 = json_encode(array('return' => $sp3ed44d), JSON_FORCE_OBJECT);
	 //echo stripslashes($sp773017);
	 } 
	 DB1::$error_handler = 'meekrodb_error_handler';
	 DB1::$throw_exception_on_error = false;
	 return $var;
} 

public function addPayment($data, $userId) {
	 if (isset($data['stripeToken'])) { $spfd0f31 = $data['stripeToken'];
	 $sp9b4a02 = $data['idClient'];
	 $sp014bab = $this->recuperaBud($sp9b4a02);
	 $sp0d3d37 = 'sk_live_raW1EN0OGM0EP4tEuV9eaMRO';
	 Stripe::setApiKey($sp0d3d37);
	 $sp0ff730 = $this->recuperaMail($sp9b4a02);
	 if (is_null($sp014bab)) { 
	 	try { 
	 		if (!isset($data['stripeToken'])) { 
	 			throw new Exception('The Stripe token was not generated correctly.');
	 } 
	 $sp54b3ba = Stripe_Customer::create(array('description' => 'Customer for ' . $sp9b4a02, 'source' => $spfd0f31, 'email' => $sp0ff730));
	 $spbc6e4d = $sp54b3ba->id;
	 $spf8d5d6 = Stripe_Customer::retrieve($spbc6e4d);
	 $sp0a5eaf = $spf8d5d6->sources->all(array('limit' => 1, 'object' => 'card'));
	 $spd5bf6b = $sp0a5eaf->data[0]->id;
	 switch ($sp0a5eaf->data[0]->brand) { case 'Visa': $sp81504e = 1;
	 break;
	 case 'MasterCard': $sp81504e = 2;
	 break;
	 case 'American Express': $sp81504e = 3;
	 break;
	 case 'Discover': $sp81504e = 4;
	 break;
	 case 'Diners Club': $sp81504e = 5;
	 break;
	 case 'JCB': $sp81504e = 6;
	 break;
	 default: $sp81504e = 1;
	 break;
	 } $sp239fc4 = $sp0a5eaf->data[0]->last4;
	 $sp857aec = $sp0a5eaf->data[0]->exp_month;
	 $sp17b1ee = $sp0a5eaf->data[0]->exp_year;
	 $sp8fe20e['idClient'] = $sp9b4a02;
	 $spa9e63e['client'] = $sp9b4a02;
	 $sp8fe20e['budespenser'] = $spbc6e4d;
	 $sp8fe20e['terenshill'] = $spd5bf6b;
	 $sp8fe20e['thefourfantastics'] = $sp239fc4;
	 $spa9e63e['last_four_digits'] = $sp239fc4;
	 $sp8fe20e['kindofmagic'] = $sp81504e;
	 $spa9e63e['type'] = $sp81504e;
	 $sp8fe20e['mes'] = $sp857aec;
	 $spa9e63e['month'] = $sp857aec;
	 $sp8fe20e['anyo'] = $sp17b1ee;
	 $spa9e63e['year'] = $sp17b1ee;
	 DB1::insert('ecocliente', $sp8fe20e);
	 $spaca90a = DB1::insertId();
	 $this->asignaTarjetas($sp9b4a02, $spaca90a);
	 $spa9e63e['id'] = $spaca90a;
	 DB1::delete('blacklisted', 'idClient=%s', $sp9b4a02);
	 $var = array('code' => 200, 'card' => $spa9e63e);
	 //$spcaf544 = json_encode(array('return' => $sp4f8b7d), JSON_FORCE_OBJECT);
	 return $var;
	 //die(stripslashes($spcaf544));
	 } catch (Exception $sp3c1f96) { 
	 	$sp4f8b7d = array('code' => -100, 'error' => $sp3c1f96->getMessage());
	 $var = array('return' => $sp4f8b7d);
	 return $var;
	 } 
	} 
	 else { 
	 	try { 

	 	if (!isset($data['stripeToken'])) { throw new Exception('The Stripe token was not generated correctly.');
	 } $sp54b3ba = Stripe_Customer::retrieve($sp014bab);
	 $sp0a5eaf = $sp54b3ba->sources->create(array('source' => $spfd0f31));
	 $spd5bf6b = $sp0a5eaf->id;
	 switch ($sp0a5eaf->brand) { case 'Visa': $sp81504e = 1;
	 break;
	 case 'MasterCard': $sp81504e = 2;
	 break;
	 case 'American Express': $sp81504e = 3;
	 break;
	 case 'Discover': $sp81504e = 4;
	 break;
	 case 'Diners Club': $sp81504e = 5;
	 break;
	 case 'JCB': $sp81504e = 6;
	 break;
	 default: $sp81504e = 1;
	 break;
	 } $sp857aec = $sp0a5eaf->exp_month;
	 $sp17b1ee = $sp0a5eaf->exp_year;
	 $sp239fc4 = $sp0a5eaf->last4;
	 $spa9e63e['client'] = $sp9b4a02;
	 $spa9e63e['last_four_digits'] = $sp239fc4;
	 $spa9e63e['type'] = $sp81504e;
	 $spa9e63e['month'] = $sp857aec;
	 $spa9e63e['year'] = $sp17b1ee;
	 try { DB1::insert('ecocliente', array('idClient' => $sp9b4a02, 'budespenser' => $sp014bab, 'terenshill' => $spd5bf6b, 'thefourfantastics' => $sp239fc4, 'kindofmagic' => $sp81504e, 'mes' => $sp857aec, 'anyo' => $sp17b1ee));
	 $spaca90a = DB1::insertId();
	$this->asignaTarjetas($sp9b4a02,$spaca90a);
	 $spa9e63e['id'] = $spaca90a;
	 $sp10fbef = DB1::queryFirstField('SELECT
						payklever_main.blacklisted.idBlack
						FROM
						payklever_main.blacklisted
						WHERE
						payklever_main.blacklisted.idClient=%s LIMIT 1', $sp9b4a02);
	 if (!is_null($sp10fbef)) { $sp29a081 = DB1::query('SELECT
							payklever_main.transaFallo.idTransaFallo,
							payklever_main.transaFallo.clienteId,
							payklever_main.transaFallo.vehiculoId,
							payklever_main.transaFallo.amount,
							payklever_main.transaFallo.fechaHora,
							payklever_main.transaFallo.parkingId,
							payklever_main.transaFallo.tiempoMin,
							payklever_main.transaFallo.idEntra,
							payklever_main.transaFallo.idSale,
							payklever_main.transaFallo.idBlackListed,
							payklever_main.transaFallo.bankMensa,
							payklever_main.transaFallo.fechaInsert
							FROM
							payklever_main.transaFallo
							WHERE
							payklever_main.transaFallo.clienteId=%s', $sp9b4a02);
	 if (!is_null($sp29a081)) { $sp209e42 = DB1::count();
	 $sp78be86 = true;
	 foreach ($sp29a081 as $sp1386ff) { $sp83a6e9 = round($sp1386ff['cobro'] * 100);
	 try { $sp22896f = Stripe_Charge::create(array('amount' => $sp83a6e9, 'currency' => 'usd', 'customer' => $sp8d6ba1, 'card' => $sp0a5eaf->id, 'description' => 'This was a purchase for ' . $sp83a6e9, 'receipt_email' => $sp0ff730, 'metadata' => array('fechahora' => $spd95746, 'parking' => $sp2b7f71, 'numero' => $spa6ade4)));
	 $sp209e42--;
	 DB1::insert('transa', array('parkingId' => $sp1386ff['parkingId'], 'idEntra' => $sp1386ff['idEntra'], 'idSale' => $sp1386ff['idSale'], 'amount' => $sp1386ff['amount'], 'vehiculoId' => $sp1386ff['vehiculoId'], 'clienteId' => $sp1386ff['clienteId'], 'tiempoMin' => $sp1386ff['tiempoMin'], 'fechaHora' => $sp1386ff['fechaHora']));
	 DB1::delete('transaFallo', 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 } catch (\Stripe\Error\Card $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 $sp78be86 = false;
	 } catch (\Stripe\Error\RateLimit $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } catch (\Stripe\Error\InvalidRequest $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } catch (\Stripe\Error\Authentication $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } catch (\Stripe\Error\ApiConnection $sp3c1f96) { $sp177538 = insertaEnListaNegra($spa6ade4, 1, $sp00a61d['message']);
	 $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } catch (\Stripe\Error\Base $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } catch (Exception $sp3c1f96) { $sp53f841 = $sp3c1f96->getJsonBody();
	 $sp0ae7ad = $sp53f841['error'];
	 DB1::update('transaFallo', array('bankMensa' => $sp0ae7ad), 'idTransaFallo=%s', $sp1386ff['idTransaFallo']);
	 if ($sp209e42 > 1) { $sp78be86 = false;
	 } } } if ($sp78be86) { DB1::delete('blacklisted', 'idBlack=%s', $sp10fbef);
	 } } 

	 else { DB1::delete('blacklisted', 'idClient=%s', $sp9b4a02);
	 } } 
	 $var = array('code' => 200, 'card' => $spa9e63e);
	 return $var;
	 //$spcaf544 = json_encode(array('return' => $sp4f8b7d), JSON_FORCE_OBJECT);
	 //die(stripslashes($spcaf544));
	 } catch (MeekroDBException $sp3c1f96) { $sp4f8b7d = array('code' => -100, 'error' => $sp3c1f96->getMessage());
	 $var = array('return' => $sp4f8b7d);
	 return $var;
	 //echo stripslashes($spcaf544);
	 } } catch (Exception $sp3c1f96) { $sp4f8b7d = array('code' => -100, 'error' => $sp3c1f96->getMessage());
	 $var = array('return' => $sp4f8b7d);
	 return $var;
	 //echo stripslashes($spcaf544);
	 } 
	} 
} else { 
	$var = array('code' => -100, 'error' => 'no token');
	return $var;
	 //$spcaf544 = json_encode(array('return' => $sp4f8b7d), JSON_FORCE_OBJECT);
	 //echo stripslashes($spcaf544);
 }
 return $var;
 }

            

}


