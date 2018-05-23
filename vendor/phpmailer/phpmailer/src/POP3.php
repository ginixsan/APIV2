<?php
namespace PHPMailer\PHPMailer; class POP3 { const VERSION = '6.0.3'; const DEFAULT_PORT = 110; const DEFAULT_TIMEOUT = 30; public $do_debug = 0; public $host; public $port; public $tval; public $username; public $password; protected $pop_conn; protected $connected = false; protected $errors = array(); const LE = '
'; public static function popBeforeSmtp($sp6f06d3, $spc61fb5 = false, $speba911 = false, $sp1c8a89 = '', $spb36729 = '', $sp5979ae = 0) { $sp7abf44 = new self(); return $sp7abf44->authorise($sp6f06d3, $spc61fb5, $speba911, $sp1c8a89, $spb36729, $sp5979ae); } public function authorise($sp6f06d3, $spc61fb5 = false, $speba911 = false, $sp1c8a89 = '', $spb36729 = '', $sp5979ae = 0) { $this->host = $sp6f06d3; if (false === $spc61fb5) { $this->port = static::DEFAULT_PORT; } else { $this->port = (int) $spc61fb5; } if (false === $speba911) { $this->tval = static::DEFAULT_TIMEOUT; } else { $this->tval = (int) $speba911; } $this->do_debug = $sp5979ae; $this->username = $sp1c8a89; $this->password = $spb36729; $this->errors = array(); $sp38622e = $this->connect($this->host, $this->port, $this->tval); if ($sp38622e) { $sp0ebd86 = $this->login($this->username, $this->password); if ($sp0ebd86) { $this->disconnect(); return true; } } $this->disconnect(); return false; } public function connect($sp6f06d3, $spc61fb5 = false, $spa9fde9 = 30) { if ($this->connected) { return true; } set_error_handler(array($this, 'catchWarning')); if (false === $spc61fb5) { $spc61fb5 = static::DEFAULT_PORT; } $this->pop_conn = fsockopen($sp6f06d3, $spc61fb5, $sp07e88d, $spb588e6, $spa9fde9); restore_error_handler(); if (false === $this->pop_conn) { $this->setError("Failed to connect to server {$sp6f06d3} on port {$spc61fb5}. errno: {$sp07e88d}; errstr: {$spb588e6}"); return false; } stream_set_timeout($this->pop_conn, $spa9fde9, 0); $spbb6492 = $this->getResponse(); if ($this->checkResponse($spbb6492)) { $this->connected = true; return true; } return false; } public function login($sp1c8a89 = '', $spb36729 = '') { if (!$this->connected) { $this->setError('Not connected to POP3 server'); } if (empty($sp1c8a89)) { $sp1c8a89 = $this->username; } if (empty($spb36729)) { $spb36729 = $this->password; } $this->sendString("USER {$sp1c8a89}" . static::LE); $spbb6492 = $this->getResponse(); if ($this->checkResponse($spbb6492)) { $this->sendString("PASS {$spb36729}" . static::LE); $spbb6492 = $this->getResponse(); if ($this->checkResponse($spbb6492)) { return true; } } return false; } public function disconnect() { $this->sendString('QUIT'); try { @fclose($this->pop_conn); } catch (Exception $sp8f120e) { } } protected function getResponse($spa21981 = 128) { $spf36e4d = fgets($this->pop_conn, $spa21981); if ($this->do_debug >= 1) { echo 'Server -> Client: ', $spf36e4d; } return $spf36e4d; } protected function sendString($sp5afdf9) { if ($this->pop_conn) { if ($this->do_debug >= 2) { echo 'Client -> Server: ', $sp5afdf9; } return fwrite($this->pop_conn, $sp5afdf9, strlen($sp5afdf9)); } return 0; } protected function checkResponse($sp5afdf9) { if (substr($sp5afdf9, 0, 3) !== '+OK') { $this->setError("Server reported an error: {$sp5afdf9}"); return false; } return true; } protected function setError($sp317af6) { $this->errors[] = $sp317af6; if ($this->do_debug >= 1) { echo '<pre>'; foreach ($this->errors as $sp8f120e) { print_r($sp8f120e); } echo '</pre>'; } } public function getErrors() { return $this->errors; } protected function catchWarning($sp07e88d, $spb588e6, $spa55934, $spe25d7d) { $this->setError('Connecting to the POP3 server raised a PHP warning:' . "errno: {$sp07e88d} errstr: {$spb588e6}; errfile: {$spa55934}; errline: {$spe25d7d}"); } }