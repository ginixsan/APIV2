<?php
require_once 'vendor/autoload.php'; require_once __DIR__ . '/../flight/autoload.php'; class RequestTest extends PHPUnit_Framework_TestCase { private $request; function setUp() { $_SERVER['REQUEST_URI'] = '/'; $_SERVER['SCRIPT_NAME'] = '/index.php'; $_SERVER['REQUEST_METHOD'] = 'GET'; $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest'; $_SERVER['REMOTE_ADDR'] = '8.8.8.8'; $_SERVER['HTTPS'] = 'on'; $_SERVER['HTTP_X_FORWARDED_FOR'] = '32.32.32.32'; $this->request = new \flight\net\Request(); } function testDefaults() { $this->assertEquals('/', $this->request->url); $this->assertEquals('/', $this->request->base); $this->assertEquals('GET', $this->request->method); $this->assertEquals('', $this->request->referrer); $this->assertEquals(true, $this->request->ajax); $this->assertEquals('HTTP/1.1', $this->request->scheme); $this->assertEquals('', $this->request->type); $this->assertEquals(0, $this->request->length); $this->assertEquals(true, $this->request->secure); $this->assertEquals('', $this->request->accept); } function testIpAddress() { $this->assertEquals('8.8.8.8', $this->request->ip); $this->assertEquals('32.32.32.32', $this->request->proxy_ip); } function testSubdirectory() { $_SERVER['SCRIPT_NAME'] = '/subdir/index.php'; $spc73b8e = new \flight\net\Request(); $this->assertEquals('/subdir', $spc73b8e->base); } function testQueryParameters() { $_SERVER['REQUEST_URI'] = '/page?id=1&name=bob'; $spc73b8e = new \flight\net\Request(); $this->assertEquals('/page?id=1&name=bob', $spc73b8e->url); $this->assertEquals(1, $spc73b8e->query->id); $this->assertEquals('bob', $spc73b8e->query->name); } function testCollections() { $_SERVER['REQUEST_URI'] = '/page?id=1'; $_GET['q'] = 1; $_POST['q'] = 1; $_COOKIE['q'] = 1; $_FILES['q'] = 1; $spc73b8e = new \flight\net\Request(); $this->assertEquals(1, $spc73b8e->query->q); $this->assertEquals(1, $spc73b8e->query->id); $this->assertEquals(1, $spc73b8e->data->q); $this->assertEquals(1, $spc73b8e->cookies->q); $this->assertEquals(1, $spc73b8e->files->q); } function testMethodOverrideWithHeader() { $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] = 'PUT'; $spc73b8e = new \flight\net\Request(); $this->assertEquals('PUT', $spc73b8e->method); } function testMethodOverrideWithPost() { $_REQUEST['_method'] = 'PUT'; $spc73b8e = new \flight\net\Request(); $this->assertEquals('PUT', $spc73b8e->method); } }