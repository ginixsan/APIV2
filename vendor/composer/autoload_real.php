<?php
class ComposerAutoloaderInit64356d868f9169af52736a498cd94dcf { private static $loader; public static function loadClassLoader($spda326a) { if ('Composer\\Autoload\\ClassLoader' === $spda326a) { require __DIR__ . '/ClassLoader.php'; } } public static function getLoader() { if (null !== self::$loader) { return self::$loader; } spl_autoload_register(array('ComposerAutoloaderInit64356d868f9169af52736a498cd94dcf', 'loadClassLoader'), true, true); self::$loader = $spa4624f = new \Composer\Autoload\ClassLoader(); spl_autoload_unregister(array('ComposerAutoloaderInit64356d868f9169af52736a498cd94dcf', 'loadClassLoader')); $sp1ec7c8 = (require __DIR__ . '/autoload_namespaces.php'); foreach ($sp1ec7c8 as $sp76b5b8 => $sp92b2da) { $spa4624f->set($sp76b5b8, $sp92b2da); } $sp1ec7c8 = (require __DIR__ . '/autoload_psr4.php'); foreach ($sp1ec7c8 as $sp76b5b8 => $sp92b2da) { $spa4624f->setPsr4($sp76b5b8, $sp92b2da); } $sp046905 = (require __DIR__ . '/autoload_classmap.php'); if ($sp046905) { $spa4624f->addClassMap($sp046905); } $spa4624f->register(true); $sp30ea20 = (require __DIR__ . '/autoload_files.php'); foreach ($sp30ea20 as $sp509382 => $sp6b4670) { composerRequire64356d868f9169af52736a498cd94dcf($sp509382, $sp6b4670); } return $spa4624f; } } function composerRequire64356d868f9169af52736a498cd94dcf($sp509382, $sp6b4670) { if (empty($sp2c8f51['__composer_autoload_files'][$sp509382])) { require $sp6b4670; $sp2c8f51['__composer_autoload_files'][$sp509382] = true; } }