<?php

require_once('vendor/autoload.php');
use Firebase\JWT\JWT;

                $private = file_get_contents('../../../parking.pem');///var/www/parking.key');
                $key='123456';
                $time = time();
                $token = array(
                    'iat' => $time, // Tiempo que inició el token
                    'exp' => $time + (120*60), // Tiempo que expirará el token (+1 hora)
                    'data' => [ // información del usuario
                        'userId' => 'uid_6798e4aec01c9b03ddeba64e81c1f06b'
                    ]
                );
                $jwt = JWT::encode($token, $private, 'RS256');
                echo $jwt;
                
?>