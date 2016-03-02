<?php

namespace Xeeeveee\Core\Container;

interface ContainerInterface {

	public function add($key, $value, $force = false);

	public function get($key);

	public function exists($key);

	public function getAll();

	public function remove($key);

	public function clear();
}