<?php
class Flight { private static $engine; private function sp43d8a6() { } private function spbbaeb4() { } private function sp291087() { } public static function __callStatic($spbc60b1, $spfbee46) { $sp2169eb = Flight::app(); return \flight\core\Dispatcher::invokeMethod(array($sp2169eb, $spbc60b1), $spfbee46); } public static function app() { static $spdb9052 = false; if (!$spdb9052) { require_once __DIR__ . '/autoload.php'; self::$engine = new \flight\Engine(); $spdb9052 = true; } return self::$engine; } }