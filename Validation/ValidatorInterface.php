<?php

namespace Xeeeveee\Core\Validation;

interface ValidatorInterface {

	public function get_rules();

	public function get_data();

	public function get_errors();

	public function passes();

	public function fails();
}