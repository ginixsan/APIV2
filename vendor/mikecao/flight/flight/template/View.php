<?php
namespace flight\template; class View { public $path; public $extension = '.php'; protected $vars = array(); private $template; public function __construct($sp92b2da = '.') { $this->path = $sp92b2da; } public function get($spa6cb11) { return isset($this->vars[$spa6cb11]) ? $this->vars[$spa6cb11] : null; } public function set($spa6cb11, $spc1f8c3 = null) { if (is_array($spa6cb11) || is_object($spa6cb11)) { foreach ($spa6cb11 as $sp027ae6 => $sp42824e) { $this->vars[$sp027ae6] = $sp42824e; } } else { $this->vars[$spa6cb11] = $spc1f8c3; } } public function has($spa6cb11) { return isset($this->vars[$spa6cb11]); } public function clear($spa6cb11 = null) { if (is_null($spa6cb11)) { $this->vars = array(); } else { unset($this->vars[$spa6cb11]); } } public function render($sp6b4670, $sp0a1a6a = null) { $this->template = $this->getTemplate($sp6b4670); if (!file_exists($this->template)) { throw new \Exception("Template file not found: {$this->template}."); } if (is_array($sp0a1a6a)) { $this->vars = array_merge($this->vars, $sp0a1a6a); } extract($this->vars); include $this->template; } public function fetch($sp6b4670, $sp0a1a6a = null) { ob_start(); $this->render($sp6b4670, $sp0a1a6a); $spb46e21 = ob_get_clean(); return $spb46e21; } public function exists($sp6b4670) { return file_exists($this->getTemplate($sp6b4670)); } public function getTemplate($sp6b4670) { $spd92396 = $this->extension; if (!empty($spd92396) && substr($sp6b4670, -1 * strlen($spd92396)) != $spd92396) { $sp6b4670 .= $spd92396; } if (substr($sp6b4670, 0, 1) == '/') { return $sp6b4670; } return $this->path . '/' . $sp6b4670; } public function e($sp059473) { echo htmlentities($sp059473); } }