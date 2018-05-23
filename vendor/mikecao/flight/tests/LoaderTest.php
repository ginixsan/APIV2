<?php
require_once 'vendor/autoload.php'; require_once __DIR__ . '/classes/User.php'; require_once __DIR__ . '/classes/Factory.php'; class LoaderTest extends PHPUnit_Framework_TestCase { private $loader; function setUp() { $this->loader = new \flight\core\Loader(); $this->loader->autoload(true, __DIR__ . '/classes'); } function testAutoload() { $this->loader->register('tests', 'User'); $sp354b42 = $this->loader->load('tests'); $this->assertTrue(is_object($sp354b42)); $this->assertEquals('User', get_class($sp354b42)); } function testRegister() { $this->loader->register('a', 'User'); $sp4de544 = $this->loader->load('a'); $this->assertTrue(is_object($sp4de544)); $this->assertEquals('User', get_class($sp4de544)); $this->assertEquals('', $sp4de544->name); } function testRegisterWithConstructor() { $this->loader->register('b', 'User', array('Bob')); $sp4de544 = $this->loader->load('b'); $this->assertTrue(is_object($sp4de544)); $this->assertEquals('User', get_class($sp4de544)); $this->assertEquals('Bob', $sp4de544->name); } function testRegisterWithInitialization() { $this->loader->register('c', 'User', array('Bob'), function ($sp4de544) { $sp4de544->name = 'Fred'; }); $sp4de544 = $this->loader->load('c'); $this->assertTrue(is_object($sp4de544)); $this->assertEquals('User', get_class($sp4de544)); $this->assertEquals('Fred', $sp4de544->name); } function testSharedInstance() { $this->loader->register('d', 'User'); $sp774d1c = $this->loader->load('d'); $sp754ebb = $this->loader->load('d'); $spd40b9e = $this->loader->load('d', false); $this->assertTrue($sp774d1c === $sp754ebb); $this->assertTrue($sp774d1c !== $spd40b9e); } function testRegisterUsingCallable() { $this->loader->register('e', array('Factory', 'create')); $sp03747f = $this->loader->load('e'); $this->assertTrue(is_object($sp03747f)); $this->assertEquals('Factory', get_class($sp03747f)); $spe3a076 = $this->loader->load('e'); $this->assertTrue(is_object($spe3a076)); $this->assertEquals('Factory', get_class($spe3a076)); $this->assertTrue($sp03747f === $spe3a076); $spe790e0 = $this->loader->load('e', false); $this->assertTrue(is_object($spe790e0)); $this->assertEquals('Factory', get_class($spe790e0)); $this->assertTrue($sp03747f !== $spe790e0); } function testRegisterUsingCallback() { $this->loader->register('f', function () { return Factory::create(); }); $sp03747f = $this->loader->load('f'); $this->assertTrue(is_object($sp03747f)); $this->assertEquals('Factory', get_class($sp03747f)); } }